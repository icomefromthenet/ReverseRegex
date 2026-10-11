# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- CI/tooling: pushing a version tag (e.g. `0.7.1`) creates the GitHub release, using the matching `CHANGELOG.md` section as notes
- CI/tooling: Dependabot checks GitHub Actions and composer dependencies monthly
- CI/tooling: the test job runs every script in `examples/` (output shown in the log), so they cannot silently break
- CI/tooling: the coverage report is uploaded to Codecov (needs the `CODECOV_TOKEN` repository secret) and the `Coverage` job fails when line coverage drops below 90%
- CI/tooling: a `Coverage` job measures code coverage with pcov, prints the report in the job summary and uploads the clover report as an artifact
### Fixed
- `generate()` docblocks (`ContextInterface`, `Scope`, `LiteralScope`) declare the by-reference `$result` as `string|null`, so static analysers such as Psalm no longer flag callers that start from `null`; runtime behaviour is unchanged
- `Parser` no longer attaches a `null` scope to the result when constructed without an explicit head
- `Parser\Short::parse()` now returns the head scope as documented
- `ArrayCollection::getAt()` now returns `null` for positions outside `1..count()` as documented, instead of a wrong element
- `Node::detach()` no longer triggers the PHP 8.5 `SplObjectStorage::detach()` deprecation
### Removed
- Unused `Test\Basic` base class (it depended on Pimple, which is not a dependency); tests now extend PHPUnit `TestCase` directly
### Changed
- PHPStan now runs at level 6 (was 5) with PHPDoc types on the previously untyped signatures and generics; no signatures changed
- Removed the PHPStan baseline: the code now passes analysis without ignored errors (`Parser\Short::convert*()` and `Parser\CharacterClass::fillRange()` now declare the `LiteralScope` they always required)
- Added native return types to `Scope`, `Node::contains()`, `Parser` and the `Parser\Quantifier`/`Parser\Unicode` strategies, and typed the `Parser` and `Quantifier` properties; the public interfaces are untouched to stay backward compatible
- All source, test and example files now declare `strict_types=1`
- Autoloading switched from PSR-0 to PSR-4 (same namespaces and class names); tests moved from `src/ReverseRegex/Test/` to `tests/` (`autoload-dev`) and are no longer shipped in the package archive
- CI/tooling: GitHub Actions `checkout`, `cache` and `upload-artifact` updated to Node 24 versions, removing the Node.js 20 deprecation warnings
- CI/tooling: PHPUnit now fails on deprecations, notices, warnings and risky tests (`error_reporting=-1`)
- CI/tooling: the test job runs on PHP 8.1 and 8.5 with both lowest and highest dependencies
- Updated development dependencies: PHPUnit 10.5, PHP-CS-Fixer 3.95, Facile.it coding standard 1.5, PHPStan 2.3
- Widened runtime dependency constraints: `doctrine/lexer` now accepts `^3` and `doctrine/collections` now accepts `^2` and `^3` (previously supported versions are still accepted)
- Lexer tokens are now read through the internal `TokenHelper`, as `doctrine/lexer` 3 no longer allows array access on tokens
- README: fixed headings, duplicated table row and typos; documented unsupported syntax (negated character classes, `\p`, `\P`)

## [0.7.0] - 2026-10-10
### Added
- `Parser::setUnboundedMax()` and `Quantifier` constructor argument to configure the maximum number of repetitions of `*` and `+`
### Changed
- `*` and `+` now repeat at most 100 times by default instead of `PHP_INT_MAX` (previously capped to ~2.8M by `SimpleRandom`)

## [0.6.1] - 2026-10-10
### Added
- Compatibility with PHP 8.5 (no deprecations found in the sources; tests added to the CI matrix)
- Makefile: run any target on PHP 8.1-8.5 via Docker (`PHP=8.x`, `test-php-8.x`, `test-all`, `cs-check`)
### Fixed
- `SplObjectStorage::attach()` deprecation notice on PHP 8.5 in `Generator\Node` (#21)
### Changed
- `docker-compose.yaml`: optional `PHP_REGISTRY` variable to pull PHP images from a registry mirror
- CI: tests now run only on the oldest (8.1) and newest (8.5) supported PHP versions; PHPStan runs on PHP 8.1 only

## [0.6.0] - 2026-03-31
### Added
- Code quality tools: Facile.it coding standard and PHPStan 2 (level 5)
- Composer scripts: `cs-check`, `cs-fix`, `phpstan`, `phpstan-baseline`
- GitHub Actions workflows for code style and static analysis (PHP 8.1-8.4)
- PHPStan baseline for incremental code quality improvements
### Fixed
- Allow SimpleRandom to handle unbounded quantifiers (#13)
### Removed
- Support for PHP 7.4 and 8.0 (minimum required version is now PHP 8.1)

## [0.5.0] - 2024-12-07
### Added
- Compatibility with PHP 8.2, 8.3, 8.4

## [0.4.0] - 2023-11-10
### Added
- Full compatibility with PHP 8.1

## [0.3.1] - 2023-04-21
### Changed
- Allow doctrine/lexer ^2
- Substitute patchwork/utf with symfony/polyfill-mbstring

## [0.3.0] - 2023-04-21
### Changed
- Versioning now adheres to semantic versioning.

## [v0.2.0.0] - 2022-22-18

### Added
- Compatibility with PHP ^8

## [v0.1.0.0 (fork)] - 2022-12-18

See https://github.com/icomefromthenet/ReverseRegex

[Unreleased]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.7.0..HEAD
[0.7.0]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.6.1..0.7.0
[0.6.1]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.6.0..0.6.1
[0.6.0]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.5.0..0.6.0
[0.5.0]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.4.0..0.5.0
[0.4.0]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.3.1..0.4.0
[0.3.1]: https://github.com/ilario-pierbattista/ReverseRegex/compare/0.3.0..0.3.1
[0.3.0]: https://github.com/ilario-pierbattista/ReverseRegex/compare/v0.2.0.0..0.3.0
[v0.2.0.0]: https://github.com/ilario-pierbattista/ReverseRegex/compare/v0.1.0.0..v0.2.0.0
[v0.1.0.0 (fork)]: https://github.com/icomefromthenet/ReverseRegex/releases/tag/v0.1.0.0
