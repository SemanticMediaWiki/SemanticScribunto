# mw.smw library functions

The following functions are available in the `mw.smw` package.

## Overview

- Data retrieval functions

    - [`mw.smw.ask`](mw.smw.ask.md)
    - [`mw.smw.getPropertyType`](mw.smw.getPropertyType.md)
    - [`mw.smw.getQueryResult`](mw.smw.getQueryResult.md)

- Data storage functions

    - [`mw.smw.set`](mw.smw.set.md)
    - [`mw.smw.subobject`](mw.smw.subobject.md)

- Miscellaneous

    - [`mw.smw.info`](mw.smw.info.md)

## Notes

### Difference between `mw.smw.ask` and `mw.smw.getQueryResult`
Both functions allow you to retrieve data from your smw store. The difference lies in the returned table. Where `mw.smw.ask`
returns a very simplistic result set (its values are all pre-formatted and already type cast), `mw.smw.getQueryResult` leaves
you with full control over your returned data, giving you abundant information but delegates all the data processing to you.

In other words:
* `ask` is a quick and easy way to get data which is already pre-processed and may not suite your needs entirely
(e.g. it does not link page properties). However it utilizes native SMW functionality like printout formatting
(see [smwdoc] for more information)
* `getQueryResult` gets you the full result set in the same format provided by the [api]

For more information see the sample results in [`mw.smw.ask`](mw.smw.ask.md) and [`mw.smw.getQueryResult`](mw.smw.getQueryResult.md).

### Using #invoke

For a detailed description of the `#invoke` function, please have a look at the [Lua reference][lua] manual.

[smwdoc]: https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki
[api]: https://www.semantic-mediawiki.org/wiki/Serialization_%28JSON%29
[lua]: https://www.mediawiki.org/wiki/Extension:Scribunto/Lua_reference_manual
