<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\DataContainer;

use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\File;
use Contao\Folder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FilesContainer implements FrameworkAwareInterface, ContainerAwareInterface
{
    use FrameworkAwareTrait;
    use ContainerAwareTrait;

    public function sanitizeFilename($dc)
    {
        $type = $dc->activeRecord->type;

        if ('folder' === $type) {
            if (null === ($folder = new Folder($dc->activeRecord->path))) {
                return;
            }

            $this->container->get('huh.filename_sanitizer.util.filename_sanitizer')->sanitizeFolder($folder);
        } else {
            if (null === ($file = new File($dc->id))) {
                return;
            }

            $this->container->get('huh.filename_sanitizer.util.filename_sanitizer')->sanitizeFile($file);
        }
    }
}
