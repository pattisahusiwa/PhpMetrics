include artifacts/Makefile

# Run unit tests
test:
	./vendor/bin/phpunit -c phpunit.xml.dist

# Codesniffer check
phpcs:
	./vendor/bin/phpcs src/ tests/ --extensions=php -n

# Codesniffer fix
phpcbf:
	./vendor/bin/phpcbf src/ tests/ --extensions=php -n

phpstan:
	tools/phpstan analyse --memory-limit 256M

# Publish new release. Usage:
#   make tag VERSION=(major|minor|patch)
# You need to install https://github.com/flazz/semver/ before
tag:
	@semver inc $(VERSION)
	@echo "New release: `semver tag`"
	@echo Releasing sources
	@sed -i -r "s/(v[0-9]+\.[0-9]+\.[0-9]+)/`semver tag`/g" \
		.github/ISSUE_TEMPLATE/Bug_report.md \
		.github/ISSUE_TEMPLATE/Feature_request.md \
		src/functions.php \
		artifacts/debian/control \
		artifacts/bintray.json \
		doc/installation.md
	@sed -i -r "s/([0-9]{4}\-[0-9]{2}\-[0-9]{2})/`date +%Y-%m-%d`/g" artifacts/bintray.json
	@make changelog-deb


# Tag git with last release
release: build
	git add .semver .github/ISSUE_TEMPLATE/Bug_report.md .github/ISSUE_TEMPLATE/Feature_request.md src/functions.php doc/installation.md artifacts/* releases/*
	git commit -S -m "releasing `semver tag`"
	git tag -s `semver tag`
	git push -u origin master
	git push origin `semver tag`
