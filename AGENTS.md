# AGENTS.md

PHP library (`ilario-pierbattista/reverse-regex`) that generates random strings from a regular expression. Fork of `icomefromthenet/reverse-regex`. See `README.md` for usage and supported syntax.

## Layout

- `src/ReverseRegex/` – library code (PSR-4 autoload: `ReverseRegex\`, `PHPStats\`).
  - `Lexer`, `Parser` (+ `Parser/*` strategies) turn a regex into a tree of `Generator/Scope` nodes.
  - `Random/*` – random number generators.
- `tests/` – PHPUnit tests (namespace `ReverseRegex\Test\`, PSR-4 `autoload-dev`).
- `examples/` – runnable usage examples.
- `CHANGELOG.md` – Keep a Changelog format; add entries under `[Unreleased]`.

## Requirements

- PHP `^8.1` (CI tests 8.1 and 8.5; the versions in between are expected to behave the same). Do not use syntax or APIs newer than 8.1.
- Docker is optional: the `Makefile` wraps commands in `docker compose` (PHP 8.1 image).

## Commands

Run directly with a local PHP, or via `make <target>` (Docker):

| Task | Command |
| --- | --- |
| Install | `composer install` (`make setup`) |
| Tests | `vendor/bin/phpunit` (`make test`) |
| Code style check | `composer cs-check` |
| Code style fix | `composer cs-fix` (`make cs-fix`) |
| Static analysis | `composer phpstan` (`make phpstan`) |
| Regenerate PHPStan baseline | `composer phpstan-baseline` (`make phpstan-update-baseline`) |

## Testing on multiple PHP versions (Docker)

The `Makefile` runs every command in a container for any PHP 8.1–8.5 (`php81`…`php85` services in `docker-compose.yaml`):

- `make test PHP=8.5` (also `cs-check`, `phpstan`, `setup`, `shell`); default is 8.1.
- `make test-php-8.5`, `make test-all` (tests on all five versions).
- `make setup PHP=8.x` first if `vendor/` is missing. All versions share the same `vendor/`.
- Docker Hub may answer `429`: set `PHP_REGISTRY=mirror.gcr.io/library/` (e.g. `PHP_REGISTRY=mirror.gcr.io/library/ make test-php-8.5`).

Cloud-sandbox caveats (checked on 2026-10-10):

- The Docker daemon is not running; start it with `(dockerd >/tmp/dockerd.log 2>&1 &)` and wait ~10s.
- Inside the sandbox the proxy blocks `docker build` network access (`apk add` fails) and blocks GitHub downloads for repos other than this one, so `composer install` cannot fetch dependencies. `make` targets therefore cannot work there; do not try to bypass the proxy.
- Fallback that does work: a syntax/deprecation lint with the stock image, e.g.
  `docker run --rm -v "$PWD":/app -w /app mirror.gcr.io/library/php:8.5-cli-alpine sh -c 'for f in $(find src examples -name "*.php"); do php -d error_reporting=-1 -l $f; done'`.
- Full test runs on other versions rely on CI (or on a local machine with working Docker/network).

## Rules

- Before finishing, tests, `cs-check` and `phpstan` must pass (all enforced by CI in `.github/workflows/`).
- Add or update tests for every behaviour change.
- Keep the public API backward compatible; this is a published library following SemVer.
- Do not add new entries to `phpstan-baseline.neon` to hide errors; fix them. Only shrink the baseline.
- Do not commit `vendor/`; `composer.lock` is not tracked.
- Update `CHANGELOG.md` under `[Unreleased]` for every user-visible change (features, fixes, supported PHP versions, CI/tooling changes affecting contributors). Do this in the same commit/PR as the change.

## Skills

Repo-specific agent skills are in `.agents/skills/` (e.g. `check-ci`, `read-issue`).
