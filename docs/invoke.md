## Using #invoke

Provides some simple examples on how to define `#invoke` functions using MediaWiki's
Module namespace. For a more detailed description about `#invoke`, please read the
[Lua reference manual][lua].

### Module:SMW

```lua
-- Module:SMW
local p = {}

-- Return results
function p.ask(frame)

    if not mw.ext.smw then
        return "mw.ext.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    else
        queryResult = mw.ext.smw.getQueryResult( frame.args[1] )
    end

    if queryResult == nil then
        return "(no values)"
    end

    return queryResult
end

-- Return property type
function p.type(frame)

    if not mw.ext.smw then
        return "mw.ext.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    else
        type = mw.ext.smw.getPropertyType( frame.args[1] )
    end

    if type == nil then
        return "(no values)"
    end

    return type
end

return p
```

- Calling `{{#invoke:smw|ask|[[Modification date::+]]|?Modification date|limit=0|mainlabel=-}}` only
makes sense in a template or another module that can handle JSON return values.
- `{{#invoke:smw|type|Modification date}}` can be used as it returns a simple string value such as `_dat`.

[lua]: https://www.mediawiki.org/wiki/Extension:Scribunto/Lua_reference_manual