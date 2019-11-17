-- module used for issue #75: https://github.com/SemanticMediaWiki/SemanticScribunto/issues/75

local p = {}

function p.set()
	local dataStore = {}
	dataStore['some property'] = mw.text.gsplit('some','strings')
	mw.smw.set( dataStore )
end

function p.subobject()
	local dataStore = {}
	dataStore['some property'] = mw.text.gsplit('some','strings')
	mw.smw.subobject( dataStore )
end

function p.subobjectId()
	mw.smw.subobject( { someData = 'FooBar' }, mw.text.gsplit('some','strings') )
end

return p
