This file contains the RELEASE-NOTES of the **Semantic Scribunto** (a.k.a. SSC) extension.

## 2.3.3

Released on October 10, 2025

* Fixes installation issues with newer versions of Semantic MediaWiki
* Bug fixes and code improvements
* Localization updates from https://translatewiki.net


## 2.3.2

Released on July 23, 2024

* Fixes PHP 7.3 issue

## 2.3.1

Released on July 16, 2024

* Fixes message registration

## 2.3.0

Released on July 16, 2024

* Raising minimum requirement to MW 1.39 and SMW 4.1
* Switch unit tests from tavis to github
* Add new ci sets for MW > 1.39
* Switch hook registration to default MW schema
* Switch to MW autoloader, abandoning composer autoloader
* Fix unit tests


## 2.2.0

Released on January 19, 2022.

* Minimum requirement for
  * PHP changed to version 7.3 and later
* #75 Fixes string conversions when passing iterators into SMW objects
* #81 Fixes "mw.smw.ask" for "format=count" queries
* #82 Fixes the display of "mw.smw.icon" when its type is set to error
* Localization updates from https://translatewiki.net

## 2.1.0

Released on August 18, 2019.

* Minimum requirement for
  * PHP changed to version 7.0 and later
  * MediaWiki changed to version 1.31 and later
  * Semantic MediaWiki changed to version 3.0 and later
* Added support for Semantic MediaWiki 3.1 and later
* Improved release testing
* Localization updates from https://translatewiki.net

## 2.0.0

Released on October 5, 2018.

* Minimum requirement for PHP changed to version 5.6 and later
* #61 Adds support for extension registration via "extension.json"  
    Now you have to use `wfLoadExtension( 'SemanticScribunto' );` in the "LocalSettings.php" file to invoke the extension
* Fixes div/span issue in info/highlighter for Semantic MediaWiki 3.0.0 and later
* Fixes some typos (by kizule)
* Translation updates from https://translatewiki.net

## 1.2.0

Released on July 19, 2018.

* #56 Support the use of subtables for values in `mw.smw.set/mw.smw.subobject`
* Translation updates from https://translatewiki.net

## 1.1.1

Released on March 27, 2018.

* #48 Fixes issue with `mw.set` dropping property names with numbers in key-value mode (by Tobias Oetterer)
* #50 Fixes failing tests due to changes in MediaWiki master (by Tobias Oetterer)
* Translation updates from https://translatewiki.net

## 1.1.0

Released on January 13, 2018.

* Minimum requirement for MediaWiki changed to version 1.27 and later
* Minimum requirement for Semantic MediaWiki changed to version 2.4 and later
* #43 Fixes issue with `mw.smw.ask` returning always "false" on printouts for category membership (by Tobias Oetterer)
* Adds `Query::PROC_CONTEXT` (by James Hong Kong)
* Translation updates from https://translatewiki.net

## 1.0.0

Released on January 23, 2017.

* Initial release
* Added the `mw.smw.lua` library with support for:
 * `smw.ask` to execute `#ask` queries (by Tobias Oetterer)
 * `smw.getQueryResult` to execute `#ask` queries
 * `smw.getPropertyType` to get the type of a property
 * `smw.info` to output tooltips equivalent to `#info` (by Tobias Oetterer)
 * `smw.set` to store data to the SMW store equivalent to `#set` (by Tobias Oetterer)
 * `smw.subobject` to store data to the SMW store equivalent to `#subobject` (by Tobias Oetterer)
