--[[
	Registers methods that can be accessed through the Scribunto extension

	@since 0.1

	@licence GNU GPL v2+
	@author mwjames
]]

-- Variable instantiation
local smw = {}
local php
local valid_value_types = { 'nil', 'boolean', 'string', 'number' }

local function validate_type( var )
	for k, v in pairs( valid_value_types ) do
		if type( var ) == v then
			return true
		end
	end
	return false
end

local function validate( parameters )
	if type( parameters ) == 'string' then
		return true
	end
	if type( parameters ) == 'table' then
		for property, value in pairs( parameters ) do
			if not validate_type( property ) or not validate_type( value ) then
				if type( value ) == 'table' then
					for k, v in pairs( value ) do
						if not validate_type( k ) or not validate_type( v ) then
							return false
						end
					end
				else
					return false
				end
			end
		end
		return true
	end
	return false
end

function smw.setupInterface()
	-- Interface setup
	smw.setupInterface = nil
	php = mw_interface
	mw_interface = nil

	-- Register library within the "mw.smw" namespace
	mw = mw or {}
	mw.smw = smw

	package.loaded['mw.smw'] = smw
end

-- ask
function smw.ask( parameters )
	return php.ask( parameters )
end

-- getPropertyType
function smw.getPropertyType( name )
	return php.getPropertyType( name )
end

-- getQueryResult
function smw.getQueryResult( queryString )
	local queryResult = php.getQueryResult( queryString )
	if queryResult == nil then return nil end
	return queryResult
end

-- info
function smw.info( text, icon )
	return php.info( text, icon )
end

-- set
function smw.set( parameters )
	if not validate( parameters ) then
		error( 'Invalid parameter type supplied to smw.set().' )
	end
	return php.set( parameters )
end

-- subobject
function smw.subobject( parameters, subobjectId )
	if not validate( parameters ) then
		error( 'Invalid parameter type supplied to smw.subobject()' )
	end
	if not validate_type( subobjectId ) then
		error( 'Invalid type for subobject id supplied to smw.subobject()' )
	end
	return php.subobject( parameters, subobjectId )
end

return smw
