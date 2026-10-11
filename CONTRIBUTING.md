# Contributing to ReverseRegex

Thanks for your interest in contributing! Bug reports, fixes, documentation improvements and new features are all welcome.

## Reporting issues

Open an issue on [GitHub](https://github.com/ilario-pierbattista/ReverseRegex/issues) and include:

- the regular expression that misbehaves,
- the expected and actual output,
- your PHP version and library version.

Check first that the issue has not already been reported. Note that not every regex feature is supported (see the "Regex Support" section of the [README](README.md)).

## Development setup

Requirements: PHP 8.1 or newer and [Composer](https://getcomposer.org/). Alternatively, Docker (see below).

```bash
git clone https://github.com/ilario-pierbattista/ReverseRegex.git
cd ReverseRegex
composer install
```

### Using Docker

The `Makefile` runs every command inside a PHP 8.1 container:

```bash
make setup      # composer install
make test       # run the test suite
make cs-fix     # fix code style
make phpstan    # static analysis
make test PHP=8.4    # any target on another version (8.1-8.5, default 8.1)
make test-php-8.3    # per-version shortcuts: setup-, test-, cs-check-, phpstan-, run-php-X.Y
make test-all        # run the tests on every supported version (8.1-8.5)
make run-php-8.5     # open a shell in a PHP 8.5 container
```

## Project layout

- `src/ReverseRegex/` – library code (lexer, parser, generators, random sources).
- `tests/` – PHPUnit tests.
- `examples/` – usage examples.

## Making changes

1. Fork the repository and create a branch from `master`.
2. Make your change, adding or updating tests that cover it.
3. Make sure all checks pass locally:

   ```bash
   vendor/bin/phpunit      # tests
   composer cs-check       # code style (run `composer cs-fix` to fix)
   composer phpstan        # static analysis
   ```

4. Add an entry under `[Unreleased]` in [CHANGELOG.md](CHANGELOG.md) for any user-visible change.
5. Open a pull request against `master` with a clear description of what and why.

CI runs the tests on the oldest (8.1) and newest (8.5) supported PHP versions, plus the code style and static analysis checks; all must be green before merging.

## Guidelines

- Code style is enforced by PHP CS Fixer with the [Facile.it coding standard](https://github.com/facile-it/facile-coding-standard).
- PHPStan is configured in `phpstan.neon`. Don't add new errors to `phpstan-baseline.neon`; fix them instead.
- Keep changes focused, and keep the public API backward compatible: the project follows [Semantic Versioning](https://semver.org/).
- Don't use PHP features newer than 8.1.

## License

By contributing you agree that your contributions are licensed under the project's [MIT license](LICENCE).
