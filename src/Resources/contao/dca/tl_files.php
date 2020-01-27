<?php

$dca = &$GLOBALS['TL_DCA']['tl_files'];

/**
 * Callbacks
 */
$dca['config']['onsubmit_callback']['sanitizeFilename'] = ['huh.filename_sanitizer.data_container.files_container', 'sanitizeFilename'];

/**
 * Fields
 */
$dca['fields']['name']['save_callback']['contao-filename-sanitizer-bundle'] = ['huh.filename_sanitizer.data_container.files_container', 'sanitizeFilenameOnSave'];