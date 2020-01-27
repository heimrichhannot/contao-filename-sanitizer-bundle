<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\EventListener;

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
        foreach ($files as $path) {
            $file = new File($path);

            $this->container->get('huh.filename_sanitizer.util.filename_sanitizer')->sanitizeFile($file);
        }
    }

    public function sanitizeFilenamesPreUpload($folder)
    {
        if (!\is_array($_FILES['files']['name'])) {
            $pathInfo = pathinfo($_FILES['files']['name']);

            $extension = isset($pathInfo['extension']) && $pathInfo['extension'] ? '.'.$pathInfo['extension'] : '';

            $_FILES['files']['name'] = $this->container->get('huh.filename_sanitizer.util.filename_sanitizer')->sanitizeString($pathInfo['filename']).$extension;
        } else {
            for ($i = 0; $i < \count($_FILES['files']['name']); ++$i) {
                if ('' == $_FILES['files']['name'][$i]) {
                    continue;
                }

                $pathInfo = pathinfo($_FILES['files']['name'][$i]);

                $extension = isset($pathInfo['extension']) && $pathInfo['extension'] ? '.'.$pathInfo['extension'] : '';

                $_FILES['files']['name'][$i] = $this->container->get('huh.filename_sanitizer.util.filename_sanitizer')->sanitizeString($_FILES['files']['name'][$i]).$extension;
            }
        }
    }
}
