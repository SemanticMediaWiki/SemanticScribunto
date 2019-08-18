# Semantic Scribunto

[![Build Status](https://secure.travis-ci.org/SemanticMediaWiki/SemanticScribunto.svg?branch=master)](http://travis-ci.org/SemanticMediaWiki/SemanticScribunto)
[![Code Coverage](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticScribunto/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/semantic-scribunto/version.png)](https://packagist.org/packages/mediawiki/semantic-scribunto)
[![Packagist download count](https://poser.pugx.org/mediawiki/semantic-scribunto/d/total.png)](https://packagist.org/packages/mediawiki/semantic-scribunto)

Semantic Scribunto (a.k.a. SSC) is a [Semantic Mediawiki][smw] extension to provide native support for the
[Scribunto][scri] extension.

## Requirements

- PHP 7.0 or later
- MediaWiki 1.31 or later
- [Semantic MediaWiki][smw] 3.0 or later

## Installation

The recommended way to install Semantic Scribunto is using [Composer](https://getcomposer.org) with
[MediaWiki's built-in support for Composer](https://www.mediawiki.org/wiki/Composer).

Note that the required extensions Semantic MediaWiki and Scribunto must be installed first according to
the installation instructions provided.

### Step 1

Change to the base directory of your MediaWiki installation. This is where the "LocalSettings.php"
file is located. If you have not yet installed Composer do it now by running the following command
in your shell:

    wget https://getcomposer.org/composer.phar

### Step 2
    
If you do not have a "composer.local.json" file yet, create one and add the following content to it:

```json
{
	"require": {
		"mediawiki/semantic-scribunto": "~2.1"
	}
}
```

If you already have a "composer.local.json" file add the following line to the end of the "require"
section in your file:

    "mediawiki/semantic-scribunto": "~2.1"

Remember to add a comma to the end of the preceding line in this section.

### Step 3

Run the following command in your shell:

    php composer.phar update --no-dev

Note if you have Git installed on your system add the `--prefer-source` flag to the above command. Also
note that it may be necessary to run this command twice. If unsure do it twice right away.

### Step 4

Add the following line to the end of your "LocalSettings.php" file:

    wfLoadExtension( 'SemanticScribunto' );

### Verify installation success

As final step, you can verify Modern Timeline got installed by looking at the "Special:Version" page on your
wiki and check that it is listed in the semantic extensions section.

## Usage

A description of the `mw.smw` library functions can be found [here](docs/README.md).

## Contribution and support

If you want to contribute work to the project please subscribe to the developers mailing list and
have a look at the contribution guideline.

* [File an issue](https://github.com/SemanticMediaWiki/SemanticScribunto/issues)
* [Submit a pull request](https://github.com/SemanticMediaWiki/SemanticScribunto/pulls)
* Ask a question on [the mailing list](https://www.semantic-mediawiki.org/wiki/Mailing_list)
* Ask a question on the #semantic-mediawiki IRC channel on Freenode.

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
