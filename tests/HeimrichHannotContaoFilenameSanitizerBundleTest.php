<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Test;

use HeimrichHannot\FilenameSanitizerBundle\HeimrichHannotContaoFilenameSanitizerBundle;
use PHPUnit\Framework\TestCase;

class HeimrichHannotContaoFilenameSanitizerBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new HeimrichHannotContaoFilenameSanitizerBundle();
        $this->assertInstanceOf(HeimrichHannotContaoFilenameSanitizerBundle::class, $bundle);
    }
}
