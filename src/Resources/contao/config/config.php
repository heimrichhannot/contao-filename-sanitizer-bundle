<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['postUpload']['sanitizeFilename'] = ['huh.filename_sanitizer.event_listener.hook_listener', 'sanitizeFilenames'];

// support for drafts bundle
$GLOBALS['TL_HOOKS']['preUpload']['sanitizeFilename']  = ['huh.filename_sanitizer.event_listener.hook_listener', 'sanitizeFilenamesPreUpload'];

$settingsService = System::getContainer()->get('huh.filename_sanitizer.data_container.settings_container');
$settingsService->setDefaults();