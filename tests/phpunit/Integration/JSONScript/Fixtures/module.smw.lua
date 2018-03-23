-- Module:SMW
local p = {}

function p.ask( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    end

    local queryResult = mw.smw.ask( frame.args )

    return convertResultTableToString( queryResult )
end

function p.getQueryResult( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    end

    local queryResult = mw.smw.getQueryResult( frame.args )

    --return mw.dumpObject( queryResult )
    return convertResultTableToString( queryResult.results )
end

function p.info( frame )
    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    end

    return mw.smw.info( frame.args[1], frame.args[2] )
end

function p.set( frame )
    if not mw.smw then
        return "mw.smw module not found"
    end

    local result = mw.smw.set( frame.args )
    if result == true then
        return 'Your data was stored successfully'
    else
        return 'An error occurred during the storage process. Message reads ' .. result.error
    end
end

function p.setWithSeparator( frame )
    if not mw.smw then
        return "mw.smw module not found"
    end

    if not frame.args.sep or not frame.args.property or not frame.args.data then
        return p.set( frame )
    end

    local query = {
        frame.args.property .. '=' .. frame.args.data,
        '+sep=' .. frame.args.sep
    }

    local result = mw.smw.set( query )

    if result == true then
        return 'Your data was stored successfully'
    else
        return 'An error occurred during the storage process. Message reads ' .. result.error
    end
end

function p.subobject( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    local args = {}
    for arg, value in pairs(frame.args) do
		args[arg] = mw.text.trim(value)
	end

    local subobjectId
    if args.subobjectId or args.SubobjectId then
        subobjectId = args.subobjectId or args.SubobjectId
        args.subobjectId = nil
		args.SubobjectId = nil
    end
    local result = mw.smw.subobject( args, subobjectId )
    if result == true then
        return 'Your data was stored successfully in a subobject'
    else
        return 'An error occurred during the subobject storage process. Message reads ' .. result.error
    end
end

function convertResultTableToString( queryResult )

    local queryResult = queryResult

    if queryResult == nil or #queryResult == 0 then
        return "(no values)"
    end

    if type( queryResult ) == "table" then
        local myResult = "<ul>"
        for num, row in ipairs( queryResult ) do
            myResult = myResult .. '<li> This is result #' .. num .. '\n<ul>'
            for property, data in pairs( row ) do
                local dataOutput = data
                if type( data ) == 'table' then
                    dataOutput = mw.text.listToText( data, ', ', ' and ')
				elseif type( data ) == 'boolean' then
					dataOutput = data and 'true' or 'false'
                end
                myResult = myResult .. '<li> ' .. property .. ': ' .. dataOutput .. '</li>'
            end
	        myResult = myResult .. '</ul></li>\n'
        end
	    myResult = myResult .. '</ul>\n'
        return myResult
    end

    return queryResult
end

function getNum( t )
	local keys = getKeys( t );
	return #keys
end

function getKeys( t )
	local keys = {}
	for k, v in pairs( t ) do
		table.insert( keys, k )
	end
	return keys
end

return p
