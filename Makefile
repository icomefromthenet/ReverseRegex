# Run the project inside Docker with any supported PHP version (8.1 - 8.5).
#
#   make test                  # tests on the default version (8.1)
#   make test PHP=8.4          # tests on PHP 8.4
#   make test-all              # tests on every supported version
#   make test-php-8.3          # tests on PHP 8.3
#   make run-php-8.5           # interactive shell in a PHP 8.5 container

PHP_VERSIONS := 8.1 8.2 8.3 8.4 8.5
PHP ?= 8.1
DK = docker compose run --rm --remove-orphans php$(subst .,,$(PHP))

.PHONY: setup test cs-check cs-fix phpstan phpstan-update-baseline shell
setup:
	$(DK) composer install
test:
	$(DK) composer test
cs-check:
	$(DK) composer cs-check
cs-fix:
	$(DK) composer cs-fix
phpstan:
	$(DK) composer phpstan
phpstan-update-baseline:
	$(DK) composer phpstan-baseline
shell:
	$(DK) sh

# Per-version shortcuts: run-php-X.Y, setup-php-X.Y, test-php-X.Y, ...
run-php-%:
	$(MAKE) shell PHP=$*
setup-php-%:
	$(MAKE) setup PHP=$*
test-php-%:
	$(MAKE) test PHP=$*
cs-check-php-%:
	$(MAKE) cs-check PHP=$*
phpstan-php-%:
	$(MAKE) phpstan PHP=$*

# Run the tests on every supported PHP version.
.PHONY: test-all
test-all: $(addprefix test-php-,$(PHP_VERSIONS))
