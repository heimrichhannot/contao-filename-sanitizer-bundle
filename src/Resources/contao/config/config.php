<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['postUpload']['sanitizeFilename'] = ['huh.filename_sanitizer.event_listener.hook_listener', 'sanitizeFilenames'];