vendor/autoload.php:
	composer install --no-interaction --prefer-dist

.PHONY: sniff
sniff: vendor/autoload.php
	vendor/bin/phpcs --standard=PSR2 -n --

.PHONY: test
test: vendor/autoload.php
	vendor/bin/phpunit --verbose