--[[
	Tests for smw.property module

	@since 0.1

	@licence GNU GPL v2+
	@author mwjames
]]

local testframework = require 'Module:TestFramework'

-- Tests
local tests = {
	{ name = 'subobject (no argument)', func = mw.smw.subobject,
		args = { '' },
		expect = { nil }
	},
	{ name = 'subobject (matching input types to property types)', func = mw.smw.subobject,
		args = { {'Has type=page', 'Allows value=test'} },
		expect = { true }
	},

	{ name = 'subobject (supplying a wrong input type to property type)', func = mw.smw.subobject,
		args = { {'Has type=test', 'Allows value=test'} },
		expect = {
			{
				false,
				error = mw.message.new('smw_unknowntype'):inLanguage('en'):plain()
				-- should be error = mw.message.new('smw_unknowntype'):inLanguage(mw.getContentLanguage()):plain()
			}
		}
	},
	{ name = 'subobject (assigning data to non existing property)', func = mw.smw.subobject,
		args = { {'Has type=page', 'Allows value=test' , '1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test'} },
		expect = { true }
	},
	{ name = 'subobject (no argument, supplying id)', func = mw.smw.subobject,
		args = { {}, '0123456780_teststringAsId' },
		expect = { nil }
	},
	{ name = 'subobject (matching input types to property types, supplying id)', func = mw.smw.subobject,
		args = { {'Has type=page', 'Allows value=test'}, '0123456780_teststringAsId' },
		expect = { true }
	},

	{ name = 'subobject (supplying a wrong input type to property type, supplying id)', func = mw.smw.subobject,
		args = { {'Has type=test', 'Allows value=test'}, '0123456780_teststringAsId' },
		expect = {
			{
				false,
				error = mw.message.new('smw_unknowntype'):inLanguage('en'):plain()
				-- should be error = mw.message.new('smw_unknowntype'):inLanguage(mw.getContentLanguage()):plain()
			}
		}
	},
	{ name = 'subobject (assigning data to non existing property, supplying id)', func = mw.smw.subobject,
		args = { {'Has type=page', 'Allows value=test' , '1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test'}, '0123456780_teststringAsId' },
		expect = { true }
	},
}

return testframework.getTestProvider( tests )