<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['postUpload']['sanitizeFilename'] = [\HeimrichHannot\FilenameSanitizerBundle\EventListener\HookListener::class, 'sanitizeFilenames'];

// support for drafts bundle
$GLOBALS['TL_HOOKS']['preUpload']['sanitizeFilename']  = [\HeimrichHannot\FilenameSanitizerBundle\EventListener\HookListener::class, 'sanitizeFilenamesPreUpload'];

$settingsService = System::getContainer()->get(\HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer::class);
$settingsService->setDefaults();
