<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class BeforeStringSanitizationEvent extends Event
{
    const NAME = 'huh.filename_sanitizer.event.before_string_sanitization_event';

    /**
     * @var string
     */
    protected $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function setString(string $string): void
    {
        $this->string = $string;
    }
}
