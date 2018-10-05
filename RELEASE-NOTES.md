This file contains the RELEASE-NOTES of the Semantic Scribunto (a.k.a. SSC) extension.

### 2.0.0

Released on October, 05, 2018

* #61 Adds support for `extension.json`
* Adds requirement to use `wfLoadExtension( 'SemanticScribunto' );` in the `LocalSettings.php`
* Bumps PHP min requirement to 5.6
* Fixes div/span issue in info/highlighter (SMW 3.0+)
* Fixes some typos (zoranzoki21)
* Translation updates from https://translatewiki.net

### 1.2.0

Released on July, 19, 2018.

* #56 Support the use of subtables for values in `mw.smw.set/mw.smw.subobject`
* Translation updates from https://translatewiki.net

### 1.1.1

Released on March 27, 2018.

* #48 Fixes issue with `mw.set` dropping property names with numbers in key-value mode (by Tobias Oetterer)
* #50 Fixes failing tests due to changes in MediaWiki master (by Tobias Oetterer)
* Translation updates from https://translatewiki.net

### 1.1.0

Released on January 13, 2018.

* Minimum requirement for MediaWiki changed to version 1.27 and later
* Minimum requirement for Semantic MediaWiki changed to version 2.4 and later
* #43 Fixes issue with `mw.smw.ask` returning always "false" on printouts for category membership (by Tobias Oetterer)
* Adds `Query::PROC_CONTEXT` (by James Hong Kong)
* Translation updates from https://translatewiki.net

### 1.0.0

Released on January 23, 2017.

* Initial release
* Added the `mw.smw.lua` library with support for:
 * `smw.ask` to execute `#ask` queries (by Tobias Oetterer)
 * `smw.getQueryResult` to execute `#ask` queries
 * `smw.getPropertyType` to get the type of a property
 * `smw.info` to output tooltips equivalent to `#info` (by Tobias Oetterer)
 * `smw.set` to store data to the SMW store equivalent to `#set` (by Tobias Oetterer)
 * `smw.subobject` to store data to the SMW store equivalent to `#subobject` (by Tobias Oetterer)
