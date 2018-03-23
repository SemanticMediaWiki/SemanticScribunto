-- Module:getQueryResult
local p = {}

function p.getQueryResultMainLabels( frame )

    local queryResult = getQueryResult( frame.args )

    return convertResultTableToString( filterForMainLabelOnly( queryResult ) )
end

function p.getQueryResultPrintRequests( frame )

    local queryResult = getQueryResult( frame.args )

    queryResult = filterForMainLabelOnly( queryResult, true )

    if queryResult then
        for num, row in pairs( queryResult ) do
            local newPrintouts = {}
            if row.printouts then
                newPrintouts = flattenPrintouts( row.printouts )
            end
            row.printouts = nil
            for property, data in pairs( newPrintouts ) do
                queryResult[num][property] = data
            end
        end
    end

    return convertResultTableToString( queryResult )
end

function p.getQueryResultCount( frame )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if frame.args[1] == nil then
        return "no parameter found"
    end

    local queryResult = mw.smw.getQueryResult( frame.args )

    if queryResult and queryResult.meta and queryResult.meta.count then
        return 'We have ' .. queryResult.meta.count .. ' result' .. ( queryResult.meta.count ~= 1 and 's' or '' ) .. '.'
    else
        return "no result count available"
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

function filterForMainLabelOnly( queryResult, keepPrintouts )

    local qr = queryResult
    if queryResult.results then
        qr = queryResult.results
    end

    if type( qr ) ~= 'table' then
        return qr
    end

    local filteredResult = {}
    for num, row in pairs( qr ) do
        if type( row ) == 'table' then
            local printOuts = row.printouts
            for field, _ in pairs( row ) do
                if field ~= 'fulltext' then
                    row[field] = nil
                end
            end
            if keepPrintouts then
                row.printouts = printOuts
            end
        end
        filteredResult[num] = row
    end
    return filteredResult
end

function flattenPrintouts( printoutsTable )
    local printoutsTable = printoutsTable
    if type( printoutsTable ) ~= 'table' then
        return printoutsTable
    else
        for property, data in pairs( printoutsTable ) do
            --[=[
            -- each property can either be
            -- * normal property (aka table containing strings / ints (?) )
            -- * 'special' property (page, date, etc) (aka table of tables containing non numeric keys)
            --]=]

            if type( data ) == 'table' then
                local newDataTable = {}
                for k, v in pairs( data ) do
                    local newValue = v
                    if type( v ) == 'table' then
                        if v.fulltext then
                            newValue = v.fulltext
                        elseif v.raw then
                            newValue = v.raw
                        else
                            newValue = 'key not known for this type'
                        end
                    end
                    table.insert( newDataTable, newValue )
                end
                printoutsTable[property] = newDataTable
            else
                -- can this happen? If so, this property has only one value.
                -- keep it untouched
            end
        end
    end
    return printoutsTable
end

function getQueryResult( arguments )

    if not mw.smw then
        return "mw.smw module not found"
    end

    if arguments[1] == nil then
        return "no parameter found"
    end

    return mw.smw.getQueryResult( arguments )
end

return p
