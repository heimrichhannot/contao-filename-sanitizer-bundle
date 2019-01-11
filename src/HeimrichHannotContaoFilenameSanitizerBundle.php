<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle;

use HeimrichHannot\FilenameSanitizerBundle\DependencyInjection\FilenameSanitizerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotContaoFilenameSanitizerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new FilenameSanitizerExtension();
    }
}
