<?php

$dca = &$GLOBALS['TL_DCA']['tl_files'];

$dca['config']['onsubmit_callback']['sanitizeFilename'] = ['huh.filename_sanitizer.data_container.files_container', 'sanitizeFilename'];