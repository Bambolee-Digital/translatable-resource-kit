# Changelog

All notable changes to `bambolee-digital/translatable-resource-kit` will be documented in this file.

## [1.0.3] - 2024-09-08

### Added
- Option to disable automatic middleware registration in config file
- New configuration option `disable_middleware` in `translatable-resource-kit.php`

### Changed
- Updated `TranslatableResourceKitServiceProvider` to respect the `disable_middleware` config option
- Updated README with instructions on how to manually register the middleware if auto-registration is disabled

### Fixed
- Minor documentation improvements and typo corrections

## [1.0.2] - 2024-09-07

### Added
- Support for Laravel 11.x
- Expanded test coverage for nested translations

### Changed
- Improved error handling in `TranslatesAttributes` trait
- Updated README with more detailed installation and usage instructions

### Fixed
- Bug in handling deeply nested relation translations

## [1.0.1] - 2024-09-05

### Added
- `lang` query parameter support in `SetLocale` middleware

### Changed
- Optimized performance of `TranslatableResource` for large datasets
- Improved type hinting throughout the package

### Fixed
- Issue with translating non-existing attributes in some edge cases

## [1.0.0] - 2024-09-01

### Added
- Initial release of Bambolee Translatable Resource Kit
- `TranslatesAttributes` trait for easy model integration
- `TranslatableResource` for API resource translation
- `TranslatableCollection` for handling collections of translatable resources
- `SetLocale` middleware for dynamic locale setting
- Configuration file for customizing package behavior
- Comprehensive README and documentation

[1.0.3]: https://github.com/bambolee-digital/translatable-resource-kit/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/bambolee-digital/translatable-resource-kit/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/bambolee-digital/translatable-resource-kit/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/bambolee-digital/translatable-resource-kit/releases/tag/v1.0.0