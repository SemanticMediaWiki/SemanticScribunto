-- module used for issue #55: https://github.com/SemanticMediaWiki/SemanticScribunto/issues/55

local p = {}

local values = {
	['Has text'] = {
		'value1',
		'value2',
		'value3'
	},
	['Has number'] = { 13, 23, 42 }
}

function p.set()
    local result = mw.smw.set(values)
    return result and 'success' or result.error
end

function p.subobject()
	local result = mw.smw.subobject(values)
	return result and 'success' or result.error
end

return p