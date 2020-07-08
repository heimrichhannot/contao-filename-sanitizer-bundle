<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Command;

use Contao\CoreBundle\Command\AbstractLockedCommand;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\File;
use Contao\Folder;
use HeimrichHannot\FilenameSanitizerBundle\Util\FilenameSanitizerUtil;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use HeimrichHannot\UtilsBundle\Database\DatabaseUtil;
use HeimrichHannot\UtilsBundle\File\FileUtil;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;
use HeimrichHannot\UtilsBundle\String\StringUtil;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SanitizeCommand extends AbstractLockedCommand
{
    /**
     * @var SymfonyStyle
     */
    protected $io;
    /**
     * @var ContaoFramework
     */
    protected $framework;
    /**
     * @var DatabaseUtil
     */
    protected $databaseUtil;
    /**
     * @var FileUtil
     */
    protected $fileUtil;

    /**
     * @var mixed
     */
    protected $ids;

    /**
     * @var mixed
     */
    protected $paths;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $dryRun;

    /**
     * @var bool
     */
    protected $recursive;
    /**
     * @var ModelUtil
     */
    private $modelUtil;
    /**
     * @var FilenameSanitizerUtil
     */
    private $util;
    /**
     * @var ContainerUtil
     */
    private $containerUtil;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    public function __construct(
        ContaoFramework $contaoFramework,
        DatabaseUtil $databaseUtil,
        ModelUtil $modelUtil,
        FileUtil $fileUtil,
        FilenameSanitizerUtil $util,
        ContainerUtil $containerUtil,
        StringUtil $stringUtil,
        $name = null
    ) {
        $this->framework = $contaoFramework;
        $this->databaseUtil = $databaseUtil;
        $this->fileUtil = $fileUtil;
        $this->modelUtil = $modelUtil;
        $this->util = $util;
        $this->containerUtil = $containerUtil;
        $this->stringUtil = $stringUtil;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('huh_filename_sanitizer:sanitize')->setDescription(
            'Sanitizes filenames in a Contao CMS instance. Please use it with caution!'
        );

        $this->addOption('dry-run', null, InputOption::VALUE_OPTIONAL, 'See what the command would do', false);

        $this->addOption('ids', null, InputOption::VALUE_OPTIONAL,
            'Pass in one or a *pipe* separated list of IDs (tl_files.id); example: 1|2|3');

        $this->addOption('paths', null, InputOption::VALUE_OPTIONAL,
            'Pass in one or a *pipe* separated list of paths (relative to the Contao root directory); example: files|files/some-folder|files/Hello, John');

        $this->addOption('recursive', 'R', InputOption::VALUE_OPTIONAL, 'Also process files/folders inside folders', false);

        $this->addOption('domain', null, InputOption::VALUE_OPTIONAL,
            'The domain for the htaccess rewrite rules');
    }

    /**
     * {@inheritdoc}
     */
    protected function executeLocked(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->rootDir = $this->getContainer()->getParameter('kernel.project_dir');

        $this->framework->initialize();

        $this->dryRun = (false !== $input->getOption('dry-run'));
        $this->recursive = (false !== $input->getOption('recursive'));

        $this->domain = $input->getOption('domain');

        if ($input->getOption('ids')) {
            $this->ids = explode('|', $input->getOption('ids'));
        } else {
            $this->ids = [];
        }

        if ($input->getOption('paths')) {
            $this->paths = explode('|', $input->getOption('paths'));

            // remove trailing slashes
            $this->paths = array_map(function ($path) {
                return $this->stringUtil->removeTrailingString('/', $path);
            }, $this->paths);
        } else {
            $this->paths = [];
        }

        $this->io->warning('Welcome to the filename sanitizer command. Please use it with caution and only if you know what you\'re doing!');

        if (empty($this->ids) && empty($this->paths)) {
            $this->io->error('You have to either pass in ids or paths in order to proceed.');

            return 0;
        }

        // avoid sql injection
        if (!empty($this->ids)) {
            $this->ids = array_map('intval', $this->ids);
        }

        // retrieve the files/folders
        $records = $this->retrieveFiles($this->ids, $this->paths);

        // filter out files already sane or producing some kind of issue
        [$hints, $records] = $this->cleanupFiles($records);

        $tableRows = [];

        foreach ($records as $path => $record) {
            $tableRows[] = [
                $path,
                $record['sanitizedFilename'],
            ];
        }

        $this->io->table(
            ['Current name', 'Name after sanitizing'],
            $tableRows
        );

        if (!empty($hints)) {
            $this->io->warning('The following files/folders will not be processed:');

            $this->io->writeln($hints);
        }

        if (empty($records)) {
            $this->io->success('No file or folder names need to be sanitized.');

            return 0;
        }

        $answer = $this->io->confirm(sprintf('The %s files/folders above will be renamed. Do you want to proceed?', \count($records)), false);

        if (!$answer) {
            return 0;
        }

        $answer = $this->io->confirm('Did you create a backup of the database table "tl_files" and your "files" folder for the worst case?', false);

        if (!$answer) {
            $this->io->error('Please create a backup of tl_files and your files folder first!');

            return 0;
        }

        $this->sanitize($records);

        $this->outputHtaccessRewriteRules($records);

        $this->io->success('Sanitizing finished.');

        return 0;
    }

    protected function sanitize($records)
    {
        foreach ($records as $path => $record) {
            $data = $record['data'];

            $this->io->write('Renaming "'.$path.'"... ');

            if ('folder' === $data['type']) {
                if (!$this->dryRun) {
                    $folder = new Folder($path);

                    $this->util->sanitizeFolder($folder);
                }
            } elseif ('file' === $data['type']) {
                if (!$this->dryRun) {
                    $file = new File($path);

                    $this->util->sanitizeFile($file);
                }
            }

            $this->io->write('Success');
            $this->io->newLine();
        }
    }

    protected function outputHtaccessRewriteRules($records)
    {
        $basePrinted = false;

        foreach ($records as $path => $record) {
            $newFile = $this->databaseUtil->findResultByPk('tl_files', $record['data']['id']);

            if (null !== $newFile && ($this->dryRun || $newFile->path !== $path) && $newFile->path) {
                if (!$basePrinted) {
                    $basePrinted = true;

                    $this->io->note('htaccess rewrite rules');
                    $this->io->writeln('RewriteEngine on');
                }

                $this->io->writeln('RewriteCond %{REQUEST_URI} "^/'.preg_quote($path).'$"');
                $this->io->writeln(sprintf('RewriteRule .* "%s/%s" [R=301,L]', $this->domain, $newFile->path));
            }
        }
    }

    protected function retrieveFiles(array $ids, array $paths)
    {
        $result = [];

        if (!empty($ids) && \is_array($ids)) {
            $records = $this->databaseUtil->findResultsBy('tl_files', ['tl_files.id IN ('.implode(',', $ids).')'], []);

            if (null !== $records && $records->numRows > 0) {
                while ($records->next()) {
                    $result[$records->path] = $records->row();

                    $result = $this->retrieveChildren($records, $result);
                }
            }
        }

        if (!empty($paths) && \is_array($paths)) {
            // special case "files"
            if (\in_array('files', $paths)) {
                $records = $this->modelUtil->callModelMethod('tl_files', 'findAll', $paths);
            } else {
                $records = $this->modelUtil->callModelMethod('tl_files', 'findMultipleByPaths', $paths);
            }

            if (null !== $records) {
                while ($records->next()) {
                    $result[$records->path] = $records->row();

                    $result = $this->retrieveChildren($records, $result);
                }
            }
        }

        ksort($result);

        return $result;
    }

    protected function retrieveChildren($record, $result)
    {
        if (!$this->recursive || 'folder' !== $record->type) {
            return $result;
        }

        // get direct children of the folder (non-recursive)
        $fileChildren = $this->modelUtil->callModelMethod('tl_files', 'findMultipleFilesByFolder', $record->path);

        if (null !== $fileChildren) {
            $result = array_merge($result, $this->retrieveFiles([], $fileChildren->fetchEach('path')));
        }

        $folderChildren = $this->modelUtil->callModelMethod('tl_files', 'findMultipleFoldersByFolder', $record->path);

        if (null !== $folderChildren) {
            $result = array_merge($result, $this->retrieveFiles([], $folderChildren->fetchEach('path')));
        }

        return $result;
    }

    protected function cleanupFiles($records)
    {
        $result = [];
        $hints = [];
        $targetPaths = [];

        foreach ($records as $path => $record) {
            $pathInfo = pathinfo($record['path']);

            $filename = $pathInfo['filename'];

            $sanitizedFilename = $this->util->sanitizeString($filename);

            $sanitized = 'folder' === $record['type'] ? $sanitizedFilename : $sanitizedFilename.($pathInfo['extension'] ? '.'.strtolower($pathInfo['extension']) : '');

            // remove files not existing
            if (!file_exists($this->containerUtil->getProjectDir().'/'.$path)) {
                $hints[] = 'Skipping "'.$path.'" since the file/folder does not exist in the file system.';

                continue;
            }

            // remove files already sane
            if ($sanitizedFilename === $filename) {
                $hints[] = 'Skipping "'.$path.'" since the filename is already sane.';

                continue;
            }

            // remove files where the sanitized version already exists in order to prevent data loss
            if (file_exists($this->containerUtil->getProjectDir().'/'.$pathInfo['dirname'].'/'.$sanitized)) {
                $hints[] = 'Skipping "'.$path.'" since a file/folder with the sanitized filename "'.$sanitized.'" already exists in the file system.';

                continue;
            }

            // remove doubled file/folder names AFTER sanitizing
            if (!\in_array($pathInfo['dirname'].'/'.$sanitized, $targetPaths)) {
                $targetPaths[] = $pathInfo['dirname'].'/'.$sanitized;
            } else {
                $hints[] = 'Skipping "'.$path.'" since a file/folder whose name would become the same like this one after sanitizing.';

                continue;
            }

            $result[$path] = [
                'data' => $record,
                'sanitizedFilename' => $sanitized,
            ];
        }

        return [$hints, $result];
    }
}
