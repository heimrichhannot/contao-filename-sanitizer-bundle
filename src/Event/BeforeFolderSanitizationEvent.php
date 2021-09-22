<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Event;

use Contao\Folder;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeFolderSanitizationEvent extends Event
{
    const NAME = 'huh.filename_sanitizer.event.before_folder_sanitization_event';

    /**
     * @var Folder
     */
    protected $folder;

    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }

    public function getFolder(): Folder
    {
        return $this->folder;
    }

    public function setFolder(Folder $folder): void
    {
        $this->folder = $folder;
    }
}
