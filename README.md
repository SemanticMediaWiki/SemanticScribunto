# Semantic Scribunto

[![Build Status](https://github.com/SemanticMediaWiki/SemanticScribunto/actions/workflows/ci.yml/badge.svg)](https://github.com/oetterer/BootstrapComponents/actions/workflows/ci.yml)
[![Code Coverage](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/?branch=master)
![Latest Stable Version](https://img.shields.io/packagist/v/mediawiki/semantic-scribunto.svg)
![Packagist Download Count](https://img.shields.io/packagist/dt/mediawiki/semantic-scribunto.svg)

Semantic Scribunto (a.k.a. SSC) is a [Semantic Mediawiki][smw] extension to provide native support for the
[Scribunto][scri] extension.

## Requirements

- PHP 7.3 or later
- MediaWiki 1.39 or later
- [Semantic MediaWiki][smw] 4.1 or later

## Installation

The recommended way to install Semantic Scribunto is using [Composer](https://getcomposer.org) with
[MediaWiki's built-in support for Composer](https://www.mediawiki.org/wiki/Composer).

Note that the required extensions Semantic MediaWiki and Scribunto must be installed first according to
the installation instructions provided.

### Step 1

Change to the base directory of your MediaWiki installation. If you do not have a "composer.local.json" file yet,
create one and add the following content to it:

```json
{
	"require": {
		"mediawiki/semantic-scribunto": "~2.3"
	}
}
```

If you already have a "composer.local.json" file add the following line to the end of the "require"
section in your file:

    "mediawiki/semantic-scribunto": "~2.3"

Remember to add a comma to the end of the preceding line in this section.

### Step 2

Run the following command in your shell:

    php composer.phar update --no-dev

Note if you have Git installed on your system add the `--prefer-source` flag to the above command.

### Step 3

Add the following line to the end of your "LocalSettings.php" file:

    wfLoadExtension( 'SemanticScribunto' );

## Usage

A description of the `mw.smw` library functions can be found [here](docs/README.md).

## Contribution and support

If you want to contribute work to the project please subscribe to the developers mailing list and
have a look at the contribution guideline.

* [File an issue](https://github.com/SemanticMediaWiki/SemanticScribunto/issues)
* [Submit a pull request](https://github.com/SemanticMediaWiki/SemanticScribunto/pulls)
* Ask a question on [the mailing list](https://www.semantic-mediawiki.org/wiki/Mailing_list)

## Tests

This extension provides unit and integration tests that are run by a [continues integration platform][travis]
but can also be executed using the `composer phpunit` command from the extension base directory that will
run all tests. In order to run only a specific test suit, the following commands are provided for convenience:

- `composer unit` to run all unit tests
- `composer integration` to run all integration tests (which requires an active MediaWiki, DB connection)

## License

[GNU General Public License, version 2 or later][gpl-licence].

[smw]: https://github.com/SemanticMediaWiki/SemanticMediaWiki
[contributors]: https://github.com/SemanticMediaWiki/SemanticScribunto/graphs/contributors
[travis]: https://travis-ci.org/SemanticMediaWiki/SemanticScribunto
[gpl-licence]: https://www.gnu.org/copyleft/gpl.html
[composer]: https://getcomposer.org/
[scri]: https://www.mediawiki.org/wiki/Extension:Scribunto
