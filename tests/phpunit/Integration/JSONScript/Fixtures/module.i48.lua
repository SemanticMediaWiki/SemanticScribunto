-- module used for issue #48: https://github.com/SemanticMediaWiki/SemanticScribunto/issues/48

local p = {}

function p.setup()
    local settings = {
        ['hello'] = 'world',
        ['hell0'] = 'world',
        'HELLO=WORLD',
        'HELL0=WORLD',
    }
    local result = mw.smw.set(settings)
    return result and 'success' or result.error
end

return p