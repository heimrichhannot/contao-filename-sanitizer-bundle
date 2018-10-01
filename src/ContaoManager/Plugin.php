<?php

namespace HeimrichHannot\FilenameSanitizerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use HeimrichHannot\FilenameSanitizerBundle\HeimrichHannotContaoFilenameSanitizerBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(HeimrichHannotContaoFilenameSanitizerBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}