--[[
	Tests for mw.ext.smw module

	@since 0.1

	@licence GNU GPL v2+
	@author mwjames
]]

local testframework = require 'Module:TestFramework'

-- Tests
local tests = {
	--getQueryResult
	{ name = 'getQueryResult (empty query)', func = mw.ext.smw.getQueryResult,
		args = { '' },
		expect = { nil }
	},
	{ name = 'getQueryResult (meta count)',
		func = function ( args )
		  local ret =  mw.ext.smw.getQueryResult( args )
		  for k,v in pairs(ret.query.printrequests ) do
		      return v.label
	  	  end
		end,
		args = { '[[Modification date::+]]|?Modification date|limit=0|mainlabel=-' },
		expect = { 'Modification date' }
	}
}

return testframework.getTestProvider( tests )
