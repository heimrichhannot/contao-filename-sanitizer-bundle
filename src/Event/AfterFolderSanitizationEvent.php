<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class AfterFolderSanitizationEvent extends Event
{
    const NAME = 'huh.filename_sanitizer.event.after_folder_sanitization_event';

    /**
     * @var string
     */
    protected $folder;

    public function __construct(string $folder)
    {
        $this->folder = $folder;
    }

    public function getFolder(): string
    {
        return $this->folder;
    }

    public function setFolder(string $folder): void
    {
        $this->folder = $folder;
    }
}
