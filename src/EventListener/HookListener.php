<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\EventListener;

use Contao\File;
use HeimrichHannot\FilenameSanitizerBundle\Util\FilenameSanitizerUtil;

class HookListener
{
    /**
     * @var FilenameSanitizerUtil
     */
    private $util;

    public function __construct(FilenameSanitizerUtil $util)
    {
        $this->util = $util;
    }

    public function sanitizeFilenames(&$files)
    {
        foreach ($files as $path) {
            $file = new File($path);

            $this->util->sanitizeFile($file);
        }
    }

    public function sanitizeFilenamesPreUpload($folder)
    {
        if (!\is_array($_FILES['files']['name'])) {
            $pathInfo = pathinfo($_FILES['files']['name']);

            $extension = isset($pathInfo['extension']) && $pathInfo['extension'] ? '.'.$pathInfo['extension'] : '';

            $_FILES['files']['name'] = $this->util->sanitizeString($pathInfo['filename']).$extension;
        } else {
            for ($i = 0; $i < \count($_FILES['files']['name']); ++$i) {
                if ('' == $_FILES['files']['name'][$i]) {
                    continue;
                }

                $pathInfo = pathinfo($_FILES['files']['name'][$i]);

                $extension = isset($pathInfo['extension']) && $pathInfo['extension'] ? '.'.$pathInfo['extension'] : '';

                $_FILES['files']['name'][$i] = $this->util->sanitizeString($_FILES['files']['name'][$i]).$extension;
            }
        }
    }
}
