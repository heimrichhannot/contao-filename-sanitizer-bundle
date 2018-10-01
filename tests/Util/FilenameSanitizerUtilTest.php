<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\UtilsBundle\Tests\Util;

use Contao\System;
use Contao\TestCase\ContaoTestCase;
use HeimrichHannot\FilenameSanitizerBundle\EventListener\FilenameSanitizerUtil;
use HeimrichHannot\UtilsBundle\String\StringUtil;

class FilenameSanitizerUtilTest extends ContaoTestCase
{
    public function setUp()
    {
        parent::setUp();

        $stringUtil = new StringUtil($this->mockContaoFramework());

        $container = $this->mockContainer();
        $container->set('huh.utils.string', $stringUtil);
        System::setContainer($container);
    }

    /**
     * Tests the object instantiation.
     */
    public function testCanBeInstantiated()
    {
        $instance = new FilenameSanitizerUtil();
        $this->assertInstanceOf(FilenameSanitizerUtil::class, $instance);
    }

    public function testSanitizeString()
    {
        $framework = $this->mockContaoFramework();
        $util = new FilenameSanitizerUtil();
        $util->setFramework($framework);

        $testData = [
            'test-file-1' => 'test-file-1',
        ];

        foreach ($testData as $input => $expectedOutput) {
            $this->assertSame($expectedOutput, $util->sanitizeString($input));
        }
    }
}
