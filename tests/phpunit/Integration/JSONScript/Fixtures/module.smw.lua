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

    return convertResultTableToString( queryResult.results )
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

function convertResultTableToString( queryResult )

    local queryResult = queryResult

    if queryResult == nil then
        return "(no values)"
    end

    if type( queryResult ) == "table" then
        local myResult = ""
        for num, row in pairs( queryResult ) do
            myResult = myResult .. '* This is result #' .. num .. '\n'
            for property, data in pairs( row ) do
                local dataOutput = data
                if type( data ) == 'table' then
                    dataOutput = mw.text.listToText( data, ', ', ' and ')
                end
                myResult = myResult .. '** ' .. property .. ': ' .. dataOutput .. '\n'
            end
        end
        return myResult
    end

    return queryResult
end

function varDump( entity, indent )
    local entity = entity
    local indent = indent and indent or ''
    if type( entity ) == 'table' then
        local output = '(table)[' .. #entity .. ']:'
        indent = indent .. '  '
        for k, v in pairs( entity ) do
            output = output .. '\n' .. indent .. k .. ': ' .. varDump( v, indent )
        end
        return output
    elseif type( entity ) == 'function' then
        return '(function)'
    elseif type( entity ) == 'bool' then
        return '(bool)' .. ( entity and 'TRUE' or 'FALSE' )
    else
        return '(' .. type( entity ) .. ') ' .. entity
    end
end

return p
