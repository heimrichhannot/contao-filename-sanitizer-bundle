<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['postUpload']['sanitizeFilename'] = ['huh.filename_sanitizer.event_listener.hook_listener', 'sanitizeFilenames'];

$settingsService = System::getContainer()->get('huh.filename_sanitizer.data_container.settings');
$settingsService->setDefaults();