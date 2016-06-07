--[[
	Registers methods that can be accessed through the Scribunto extension

	@since 0.1

	@licence GNU GPL v2+
	@author mwjames
]]

-- Variable instantiation
local smw = {}
local php

function smw.setupInterface()
	-- Interface setup
	smw.setupInterface = nil
	php = mw_interface
	mw_interface = nil

	-- Register library within the "mw.ext.smw" namespace
	mw = mw or {}
	mw.ext = mw.ext or {}
	mw.ext.smw = smw

	package.loaded['mw.ext.smw'] = smw
end

-- getQueryResult
function smw.getQueryResult( queryString )
	local queryResult = php.getQueryResult( queryString )
	if queryResult == nil then return nil end
	return queryResult
end

-- getPropertyType
function smw.getPropertyType( name )
	local propertyType = php.getPropertyType( name )
	if propertyType == nil then return nil end
	return propertyType
end

return smw