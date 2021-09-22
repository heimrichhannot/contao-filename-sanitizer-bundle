<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class AfterFilenameSanitizationEvent extends Event
{
    const NAME = 'huh.filename_sanitizer.event.after_filename_sanitization_event';

    /**
     * @var string
     */
    protected $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }
}
