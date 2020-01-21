<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\UtilsBundle\Tests\Util;

use Contao\Config;
use Contao\System;
use Contao\TestCase\ContaoTestCase;
use HeimrichHannot\FilenameSanitizerBundle\DataContainer\Settings;
use HeimrichHannot\FilenameSanitizerBundle\Util\FilenameSanitizerUtil;

class FilenameSanitizerUtilTest extends ContaoTestCase
{
    public function setUp()
    {
        parent::setUp();

        $settingsService = new Settings();
        $settingsService->setFramework($this->mockContaoFramework());

        $container = $this->mockContainer();
        $container->set('huh.filename_sanitizer.data_container.settings_container', $settingsService);
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

        /** @var Settings $settingsService */
        $settingsService = System::getContainer()->get('huh.filename_sanitizer.data_container.settings_container');

        /*
         * Alphabets
         */

        // capital letters
        Config::set('fs_validAlphabets', serialize([$settingsService::CAPITAL_LETTERS]));
        $this->assertSame('TESTTESTTEST', $util->sanitizeString('Test test-1234_tesT'));

        // small letters
        Config::set('fs_validAlphabets', serialize([$settingsService::SMALL_LETTERS]));
        $this->assertSame('testtesttest', $util->sanitizeString('Test test-1234_tesT'));

        // numbers
        Config::set('fs_validAlphabets', serialize([$settingsService::NUMBERS]));
        $this->assertSame('1234', $util->sanitizeString('Test test-1234_tesT'));

        // special chars
        Config::set('fs_validAlphabets', serialize([$settingsService::SPECIAL_CHARS]));
        Config::set('fs_validSpecialChars', '-_');
        $this->assertSame('-_', $util->sanitizeString('Test test-1234_tesT'));

        Config::set('fs_validSpecialChars', '- _');
        $this->assertSame(' -_', $util->sanitizeString('Test test-1234_tesT'));

        /*
         * replace character
         */
        Config::set('fs_validAlphabets', serialize($settingsService::DEFAULTS['fs_validAlphabets']));
        Config::set('fs_replaceChar', '@');
        Config::set('fs_validSpecialChars', '-_');
        $this->assertSame('test@test-1234_test', $util->sanitizeString('Test test-1234_tesT'));

        Config::set('fs_replaceChar', '-');
        $this->assertSame('test--test--1234_test', $util->sanitizeString('Test  test-@1234_tesT'));

        /*
         * condensed separators
         */
        Config::set('fs_validAlphabets', serialize($settingsService::DEFAULTS['fs_validAlphabets']));
        Config::set('fs_replaceChar', '-');
        Config::set('fs_validSpecialChars', '-_');
        Config::set('fs_condenseSeparators', true);

        $this->assertSame('test-test-1234_test', $util->sanitizeString('Test  test-@1234_tesT'));

        /*
         * trim
         */
        $this->assertSame('-test-', $util->sanitizeString('  test   '));

        /*
         * final default alphabets and special chars containing all features at once
         */
        foreach ($settingsService::DEFAULTS as $field => $value) {
            Config::set($field, \is_array($value) ? serialize($value) : $value);
        }

        $this->assertSame('test-test-1234-test', $util->sanitizeString('Test  test--1234_tesT'));
    }
}
