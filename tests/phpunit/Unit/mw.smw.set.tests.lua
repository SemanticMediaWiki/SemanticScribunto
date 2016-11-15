--[[
	Tests for smw.property module

	@since 0.1

	@licence GNU GPL v2+
	@author mwjames
]]

local testframework = require 'Module:TestFramework'

-- Tests
local tests = {
	{ name = 'set (no argument)', func = mw.smw.set,
		args = { '' },
		expect = { nil }
	},
	{ name = 'set (matching input type to property type)', func = mw.smw.set,
		args = { 'has type=page' },
		expect = { true }
	},
	{ name = 'set (supplying wrong input type to property type)', func = mw.smw.set,
		args = { 'has type=test' },
		expect = {
			{
				false,
				error = mw.message.new('smw_unknowntype'):inLanguage(mw.getContentLanguage()):plain()
			}
		}
	},
	{ name = 'set (assigning data to non existing property)', func = mw.smw.set,
		args = { '1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' },
		expect = { true }
	}
}

return testframework.getTestProvider( tests )