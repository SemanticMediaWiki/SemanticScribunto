## mw.smw library functions

The following functions are available:

- [`mw.smw.getQueryResult`](#mw.smw.getQueryResult)
- [`mw.smw.getPropertyType`](#mw.smw.getPropertyType)

### mw.smw.getQueryResult

```lua
-- Module:SMW
local p = {}

-- Return results
function p.ask(frame)

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    else
        queryResult = mw.smw.getQueryResult( frame.args[1] )
    end

    if queryResult == nil then
        return "(no values)"
    end

    if type( queryResult ) == "table" then
        myResult = ""
        for k,v in pairs( queryResult.results ) do
            myResult = myResult .. k .. " | " .. v.fulltext .. " " .. v.fullurl .. " | " .. "<br/>"
        end
        return myResult
    end

    return queryResult
end
```

Calling `{{#invoke:smw|ask|[[Modification date::+]]|?Modification date|limit=0|mainlabel=-}}` only
makes sense in a template or another module that can handle `table` return values.

### mw.smw.getPropertyType

```
-- Module:SMW
local p = {}

-- Return property type
function p.type(frame)

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    else
        type = mw.smw.getPropertyType( frame.args[1] )
    end

    if type == nil then
        return "(no values)"
    end

    return type
end

return p
```

`{{#invoke:smw|type|Modification date}}` can be used as it returns a simple string value such as `_dat`.

## Using #invoke

For a detailed description about the `#invoke`, please have a look at the [Lua reference][lua] manual.

[lua]: https://www.mediawiki.org/wiki/Extension:Scribunto/Lua_reference_manual
