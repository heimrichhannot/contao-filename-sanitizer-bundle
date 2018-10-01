# Contao Filename Sanitizer Bundle

This bundle offers functionality for sanitizing filenames, i.e. replacing unwanted characters like whitespaces, non-ascii characters, ... (e.g. while uploading them to the CMS).

## Features

- configurable sanitizing rules:
  - valid alphabets (the characters which are valid in the end -> "whitelist")
  - trimming
  - replacing of repeating (consecutive) hyphens or underscores

## Installation

Install via composer: `composer require heimrichhannot/contao-filename-sanitizer-bundle` and update your database.