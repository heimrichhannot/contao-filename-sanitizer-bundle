# Changelog
All notable changes to this project will be documented in this file.

## [2.2.1] - 2020-07-09

- fixed mac os umlaut issue
- fixed tests

## [2.2.0] - 2020-07-08

- added handling for rare problematic situations in `SanitizeCommand` (e.g. sanitizing a file when a file of the sanitized name already exists) in order to prevent data loss
- improved error logging in `SanitizeCommand`

## [2.1.0] - 2020-07-07

- added `SanitizeCommand` for fixing filenames of files already in the system (use it with caution and always do a backup!)
- class/service refactoring for contao 4.4 and 4.9

## [2.0.1] - 2020-01-27

- fixed changelog

## [2.0.0] - 2020-01-27

- added the functionality also to save_callback in order to have field validation
- added support for contao-drafts-bundle
- improved file path handling

## [1.3.2] - 2020-01-21

- backend file save issue

## [1.3.1] - 2019-03-20

### Fixed
- js issues
- character replacement can now be empty

## [1.3.0] - 2019-03-19

### Added
- sorting for char replacement
- version 2 of `heimrichhannot/contao-multi-column-editor-bundle` as dependency

## [1.2.1] - 2019-03-18

### Added
- `heimrichhannot/contao-multi-column-editor-bundle` as dependency

## [1.2.0] - 2019-03-18

### Added
- character replacement feature
- English localization
- default character replacement for German umlauts (can be deactivated in global Contao settings)
- multiple symfony events
- image for readme

### Changed
- internal service naming

## [1.1.2] - 2019-03-06

### Fixed
- issue with uploading files or folders with already existing sanitized name
- added termination condition for `filename/foldername === sanitized filename`

## [1.1.1] - 2019-01-18

### Fixed
- translation issue

## [1.1.0] - 2019-01-18

### Added
- support for folders

## [1.0.0] - 2019-01-11

### Added
- initial commit
