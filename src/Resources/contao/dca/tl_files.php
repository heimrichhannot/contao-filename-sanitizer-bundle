<?php

$dca = &$GLOBALS['TL_DCA']['tl_files'];

/**
 * Callbacks
 */
$dca['config']['onsubmit_callback']['sanitizeFilename'] = [\HeimrichHannot\FilenameSanitizerBundle\DataContainer\FilesContainer::class, 'sanitizeFilename'];

/**
 * Fields
 */
$dca['fields']['name']['save_callback']['contao-filename-sanitizer-bundle'] = [
    \HeimrichHannot\FilenameSanitizerBundle\DataContainer\FilesContainer::class, 'sanitizeFilenameOnSave'
];
