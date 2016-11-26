## mw.smw library functions

The following functions are available through the `mw.smw` package:

### Overview

- Data retrieval functions

    - [`mw.smw.getQueryResult`](#mwsmwgetQueryResult)
    - [`mw.smw.getPropertyType`](#mwsmwgetPropertyType)

- Data storage functions

    - [`mw.smw.info`](#mwsmwinfo)
    - [`mw.smw.set`](#mwsmwset)
    - [`mw.smw.subobject`](#mwsmwsubobject)

### Data retrieval functions

#### mw.smw.getQueryResult

With `mw.smw.getQueryResult` you can execute an smw query. It returns the result as a lua table for direct use in modules.
For available parameters, please consult the [Semantic Media Wiki documentation hub][smwdoc].

This is a sample call:
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
    end

    local queryResult = mw.smw.getQueryResult( frame.args )

    if queryResult == nil then
        return "(no values)"
    end

    if type( queryResult ) == "table" then
        local myResult = ""
        for k,v in pairs( queryResult.results ) do
            if  v.fulltext and v.fullurl then
                myResult = myResult .. k .. " | " .. v.fulltext .. " " .. v.fullurl .. " | " .. "<br/>"
            else
                myResult = myResult .. k .. " | no page title for result set available (you probably specified ''mainlabel=-')"
            end
        end
        return myResult
    end

    return queryResult
end

return p
```

The return format matches the data structure delivered by the [api]. You can see an example below:
```lua
-- assuming sample call
local result = mw.smw.getQueryResult( '[[Modification date::+]]|?Modification date|?Last editor is|limit=2|mainlabel=page' )
-- your result would look something like
{
    printrequests = {
        {
            label = 'page',
            redi = null,
            typeid = '_wpg',
            mode = 2,
            format = null
        },
        {
            label = 'Modification date',
            key = '_MDAT',
            redi = null,
            typeid = '_dat',
            mode = 1,
            format = null
        },
        {
            label = 'editor',
            key = '_LEDT',
            redi = null,
            typeid = '_wpg',
            mode = 1,
            format = null
        },
    },
    result = {
        {
            printouts = {
                ['Modification date'] = {
                    {
                        timestamp = 123456789, -- a unix timestamp
                        raw = '1/1970/1/1/23/59/59/0'
                    }
                },
                editor = {
                    {
                        fulltext = 'User:Mwjames',
                        fullurl = 'https://your.host/w/User:Mwjames'
                    }
                }
            },
            fulltext = 'Main page',
            fullurl = 'https://your.host/w/Main_page',
            namespace = 0,
            exist = 1,
            displaytitle = ''
        },
        {
            printouts = {
                ['Modification date'] = {
                    {
                        timestamp = 123456790, -- a unix timestamp
                        raw = '1/1970/1/2/0/0/1/0'
                    }
                },
                editor = {
                    {
                        fulltext = 'User:Matthew-a-thompson',
                        fullurl = 'https://your.host/w/User:Matthew-a-thompson'
                    }
                }
            },
            fulltext = 'User:Matthew A Thompson',
            fullurl = 'https://your.host/w/User:Matthew_A_Thompson',
            namespace = 2,
            exist = 1,
            displaytitle = ''
        },
    },
    serializer = 'SMW\Serializers\QueryResultSerializer',
    version = 0.11,
    meta = {
        hash = '5b2187c3df541ca08d378b3690a31173',
        count = 2,  -- number of results
        offset = 0, -- used offset
        source = null,
        time = 0.000026,
    }
}
```

Calling `{{#invoke:smw|ask|[[Modification date::+]]|?Modification date|limit=0|mainlabel=-}}` only
makes sense in a template or another module that can handle `table` return values.

#### mw.smw.getPropertyType
The function `mw.smw.getPropertyType` provides an easy way to get the type of a given property.
Note however, that it uses the smw internal property types, not the one you might be [used to](https://www.semantic-mediawiki.org/wiki/Help:List_of_datatypes).

```lua
-- Module:SMW
local p = {}

-- Return property type
function p.type(frame)

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    end
    local pType = mw.smw.getPropertyType( frame.args[1] )

    if pType == nil then
        return "(no values)"
    end

    return pType
end

return p
```

`{{#invoke:smw|type|Modification date}}` can be used as it returns a simple string value such as `_dat`.

### Data storage functions

#### mw.smw.set
This makes the smw parser function `#set` available in lua and allows you to store data in your smw store.
The usage is similar to that of the [parser function][set], however be advised to read the notes under the example.

This is a sample call:
```lua
-- Module:SMW
local p = {}

-- set with return results
function p.set( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if #frame.args == 0 then
        return "no parameters found"
    end
    local result = mw.smw.set( frame.args )
    if result == true then
        return 'Your data was stored successfully'
    else
        return 'An error occurred during the storage process. Message reads ' .. result.error
    end
end

-- another example, set used inside another function
function p.inlineSet( frame )

    local dataStoreTyp1 = {}

    dataStoreTyp1['my property1'] = 'value1'
    dataStoreTyp1['my property2'] = 'value2'
    dataStoreTyp1['my property3'] = 'value3'

    local result = mw.smw.set( dataStoreTyp1 )

    if result == true then
        -- everything ok
    else
        -- error message to be found in result.error
    end

    local dataStoreType2 = {
        'my property1=value1',
        'my property2=value2',
        'my property3=value3',
    }

    local result = mw.smw.set( dataStoreType2 )

    if result == true then
        -- everything ok
    else
        -- error message to be found in result.error
    end
end

return p
```
As you can see, you can supply arguments to `mw.smw.set` as either an associative array and as lua table.

**Note** however: lua does not maintain the order in an associative array. Using parameters for `set` like the [separator](https://www.semantic-mediawiki.org/wiki/Help:Setting_values/Working_with_the_separator_parameter)
or the [template parameter](https://www.semantic-mediawiki.org/wiki/Help:Setting_values/Working_with_the_template_parameter) requires a strict parameter order
in which case you must use the table format as shown with *dataStoreType2* in the example above.


SMW.set can be invoked via `{{#invoke:smw|set|my property1=value1|my property2=value2}}`. However, using the lua function in this way makes little sense.
It is designed to be used inside your modules.

#### mw.smw.subobject
This makes the smw parser function `#subobject` available in lua and allows you to store data in your smw store as subobjects.
The usage is similar to that of the [parser function][subobject] but be advised to read the notes for [mw.smw.info](#mw.smw.info) as well.

This is a sample call:
```lua
-- Module:SMW
local p = {}

-- subobject with return results
function p.subobject( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if #frame.args == 0 then
        return "no parameters found"
    end
    local result = mw.smw.subobject( frame.args )
    if result == true then
        return 'Your data was stored successfully in a subobject'
    else
        return 'An error occurred during the subobject storage process. Message reads ' .. result.error
    end
end
-- another example, subobject used inside another function
function p.inlineSubobject( frame )

    local dataStoreTyp1 = {}

    dataStoreTyp1['my property1'] = 'value1'
    dataStoreTyp1['my property2'] = 'value2'
    dataStoreTyp1['my property3'] = 'value3'

    local result = mw.smw.subobject( dataStoreTyp1 )

    if result == true then
        -- everything ok
    else
        -- error message to be found in result.error
    end

    local dataStoreType2 = {
        'my property1=value1',
        'my property2=value2',
        'my property3=value3',
    }

    local result = mw.smw.subobject( dataStoreType2 )

    if result == true then
        -- everything ok
    else
        -- error message to be found in result.error
    end

    -- you can also manually set a subobject id. however, this is not recommended

    local result = mw.smw.subobject( dataStoreType2, 'myPersonalId' )
    if result == true then
        -- everything ok
    else
        -- error message to be found in result.error
    end
end

return p
```

### miscellaneous
#### mw.smw.info
This makes the smw parser function `#info` available in lua and allows you to add tooltips to your output stream. See [the documentation on the SMW homepage][info] for more information.

This is a sample call:
```lua
-- Module:SMW
local p = {}

-- set with direct return results
function p.info( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    end

    local tooltip
    if frame.args[2] then
        tooltip = mw.smw.info( frame.args[1], frame.args[2] )
    else
        tooltip = mw.smw.info( frame.args[1] )
    end

    return tooltip
end
-- another example, info used inside another function
function p.inlineInfo( frame )

    local output = 'This is sample output'

    -- so some stuff

    output = output .. mw.smw.info( 'This is a warning', 'warning' )

    -- some more stuff

    return output
end

return p
```

### notes
## Using #invoke

For a detailed description about the `#invoke`, please have a look at the [Lua reference][lua] manual.

[lua]: https://www.mediawiki.org/wiki/Extension:Scribunto/Lua_reference_manual
[api]: https://www.semantic-mediawiki.org/wiki/Serialization_%28JSON%29
[smwdoc]: https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki
[set]: https://www.semantic-mediawiki.org/wiki/Help:Setting_values
[subobject]: https://www.semantic-mediawiki.org/wiki/Help:Adding_subobjects
[info]: https://www.semantic-mediawiki.org/wiki/Help:Adding_tooltips
