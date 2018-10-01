<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\File;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class HookListener implements FrameworkAwareInterface, ContainerAwareInterface
{
    use FrameworkAwareTrait;
    use ContainerAwareTrait;

    public function sanitizeFilenames(&$files)
    {
        // generate valid alphabet for replacement
        //Config::get('fs_validAlphabets'), Config::get('fs_validAlphabets'), Config::get('fs_validSpecialChars')

        foreach ($files as $path) {
            $file = new File($path);

            if ($file->exists()) {
                $strFilename = str_replace('.'.$file->extension, '', $file->name);
                $strFolder = str_replace($file->name, '', $path);
                $strFilename = StringUtil::convertGermanSpecialLetters($strFilename);
                $path = $strFolder.Files::sanitizeFileName($strFilename).'.'.$file->extension;
                $file->renameTo($path);

                if (version_compare(VERSION, '4.0', '<')) {
                    $file->close();
                }
            }
        }
    }
}
