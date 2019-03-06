# Contao Filename Sanitizer Bundle

This bundle offers functionality for sanitizing filenames, i.e. replacing unwanted characters like whitespaces, non-ascii characters, ... (e.g. while uploading them to the CMS).

## Features

- sanitize filenames while uploading files using the Contao file manager
- sanitize filenames while saving a given file using the Contao file manager
- configurable sanitizing rules:
  - valid alphabets (the characters which are valid in the end -> "whitelist")
  - trimming
  - replacing of repeating (consecutive) hyphens or underscores
- service is also available for various other use cases besides file upload

## Installation

Install via composer: `composer require heimrichhannot/contao-filename-sanitizer-bundle` and update your database.

## Configuration

You can configure the sanitizing rules in the global Contao settings under "file names".