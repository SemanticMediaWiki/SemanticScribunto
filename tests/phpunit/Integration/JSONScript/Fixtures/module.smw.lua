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

    local queryResult = mw.smw.ask( frame.args )

    if queryResult == nil then
        return "(no values)"
    end

    if type( queryResult ) == "table" then
        local myResult = ""
        for num, row in pairs( queryResult ) do
            myResult = myResult .. '* This is result #' .. num .. '\n'
            for k, v in pairs( row ) do
                myResult = myResult .. '** ' .. k .. ': ' .. v .. '\n'
            end
        end
        return myResult
    end

    return queryResult
end

-- another example, ask used inside another function
function p.inlineAsk()

    local entityAttributes = {
        'has name=name',
        'has age=age',
        'has color=color'
    }
    local category = 'thingies'

    -- build query
    local query = {}
    table.insert(query, '[[Category:' .. category .. ']]')

    for _, v in pairs( entityAttributes ) do
        table.insert( query, '?' .. v )
    end

    query.mainlabel = 'origin'
    query.limit = 10

    local result = mw.smw.ask( query )

    local output = ''
    if result and #result then

        for num, entityData in pairs( result ) do
            -- further process your data
            output = output .. entityData.origin .. ' (' .. num .. ') has name ' .. entityData.name
                .. ', color ' .. entityData.color .. ', and age ' .. entityData.age
        end
    end

    return output
end

return p
