<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Event;

use Contao\File;
use Symfony\Component\EventDispatcher\Event;

class BeforeFilenameSanitizationEvent extends Event
{
    const NAME = 'huh.filename_sanitizer.event.before_filename_sanitization_event';

    /**
     * @var File
     */
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): void
    {
        $this->file = $file;
    }
}
