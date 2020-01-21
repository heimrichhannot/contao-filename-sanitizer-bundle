<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Test\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Parser\DelegatingParser;
use Contao\TestCase\ContaoTestCase;
use HeimrichHannot\FilenameSanitizerBundle\ContaoManager\Plugin;
use HeimrichHannot\FilenameSanitizerBundle\HeimrichHannotContaoFilenameSanitizerBundle;

class PluginTest extends ContaoTestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(Plugin::class, new Plugin());
    }

    public function testGetBundles()
    {
        $plugin = new Plugin();
        $bundles = $plugin->getBundles(new DelegatingParser());
        $this->assertCount(1, $bundles);
        $this->assertSame(HeimrichHannotContaoFilenameSanitizerBundle::class, $bundles[0]->getName());
        $this->assertSame(ContaoCoreBundle::class, $bundles[0]->getLoadAfter()[0]);
    }
}
