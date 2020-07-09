<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Tests\Util;

use Contao\Config;
use Contao\System;
use Contao\TestCase\ContaoTestCase;
use HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer;
use HeimrichHannot\FilenameSanitizerBundle\Util\FilenameSanitizerUtil;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use HeimrichHannot\UtilsBundle\Url\UrlUtil;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FilenameSanitizerUtilTest extends ContaoTestCase
{
    public function setUp()
    {
        parent::setUp();

        $container = $this->mockContainer();

        $settingsContainer = new SettingsContainer();
        $containerUtil = $this->createMock(ContainerUtil::class);
        $urlUtil = $this->createMock(UrlUtil::class);

        $container->set(EventDispatcherInterface::class, new EventDispatcher());
        $container->set(SettingsContainer::class, $settingsContainer);
        $container->set(ContainerUtil::class, $containerUtil);
        $container->set(UrlUtil::class, $urlUtil);
        System::setContainer($container);
    }

    /**
     * Tests the object instantiation.
     */
    public function testCanBeInstantiated()
    {
        $instance = new FilenameSanitizerUtil(
            System::getContainer()->get(EventDispatcherInterface::class),
            System::getContainer()->get(\HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer::class),
            System::getContainer()->get(\HeimrichHannot\UtilsBundle\Container\ContainerUtil::class),
            System::getContainer()->get(\HeimrichHannot\UtilsBundle\Url\UrlUtil::class)
        );
        $this->assertInstanceOf(FilenameSanitizerUtil::class, $instance);
    }

    public function testSanitizeString()
    {
        $util = new FilenameSanitizerUtil(
            System::getContainer()->get(EventDispatcherInterface::class),
            System::getContainer()->get(\HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer::class),
            System::getContainer()->get(\HeimrichHannot\UtilsBundle\Container\ContainerUtil::class),
            System::getContainer()->get(\HeimrichHannot\UtilsBundle\Url\UrlUtil::class)
        );

        /** @var SettingsContainer $settingsContainer */
        $settingsContainer = System::getContainer()->get(\HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer::class);

        /*
         * Alphabets
         */
        // capital letters
        Config::set('fs_validAlphabets', serialize([$settingsContainer::CAPITAL_LETTERS]));
        $this->assertSame('TESTTESTTEST', $util->sanitizeString('Test test-1234_tesT'));

        // small letters
        Config::set('fs_validAlphabets', serialize([$settingsContainer::SMALL_LETTERS]));
        $this->assertSame('testtesttest', $util->sanitizeString('Test test-1234_tesT'));

        // numbers
        Config::set('fs_validAlphabets', serialize([$settingsContainer::NUMBERS]));
        $this->assertSame('1234', $util->sanitizeString('Test test-1234_tesT'));

        // special chars
        Config::set('fs_validAlphabets', serialize([$settingsContainer::SPECIAL_CHARS]));
        Config::set('fs_validSpecialChars', '-_');
        $this->assertSame('-_', $util->sanitizeString('Test test-1234_tesT'));

        Config::set('fs_validSpecialChars', '- _');
        $this->assertSame(' -_', $util->sanitizeString('Test test-1234_tesT'));

        /*
         * replace character
         */
        Config::set('fs_validAlphabets', serialize($settingsContainer::DEFAULTS['fs_validAlphabets']));
        Config::set('fs_replaceChar', '@');
        $this->assertSame('test@test@1234@test', $util->sanitizeString('Test test-1234_tesT'));

        Config::set('fs_replaceChar', '-');
        $this->assertSame('test--test--1234-test', $util->sanitizeString('Test  test-@1234_tesT'));

        /*
         * condensed separators
         */
        Config::set('fs_validAlphabets', serialize($settingsContainer::DEFAULTS['fs_validAlphabets']));
        Config::set('fs_replaceChar', '-');
        Config::set('fs_condenseSeparators', true);

        $this->assertSame('test-test-1234-test', $util->sanitizeString('Test  test-@1234_tesT'));

        /*
         * trim
         */
        $this->assertSame('-test-', $util->sanitizeString('  test   '));

        /*
         * final default alphabets and special chars containing all features at once
         */
        foreach ($settingsContainer::DEFAULTS as $field => $value) {
            Config::set($field, \is_array($value) ? serialize($value) : $value);
        }

        $this->assertSame('test-test-1234-test', $util->sanitizeString('Test  test--1234_tesT'));
    }
}
