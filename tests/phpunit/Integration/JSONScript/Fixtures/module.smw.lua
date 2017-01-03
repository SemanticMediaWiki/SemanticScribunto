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

return p
