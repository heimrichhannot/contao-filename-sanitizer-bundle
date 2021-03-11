<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\DataContainer;

use Contao\File;
use Contao\Folder;
use HeimrichHannot\FilenameSanitizerBundle\Util\FilenameSanitizerUtil;

class FilesContainer
{
    /**
     * @var FilenameSanitizerUtil
     */
    private $util;

    public function __construct(FilenameSanitizerUtil $util)
    {
        $this->util = $util;
    }

    public function sanitizeFilename($dc)
    {
        $type = $dc->activeRecord->type;

        if ('folder' === $type) {
            if (null === ($folder = new Folder($dc->activeRecord->path))) {
                return;
            }

            $this->util->sanitizeFolder($folder);
        } else {
            if (null === ($file = new File($dc->activeRecord->path))) {
                return;
            }

            $this->util->sanitizeFile($file);
        }
    }

    public function sanitizeFilenameOnSave($value, $dc)
    {
        return $this->util->sanitizeString($value);
    }
}
