--[[
	Tests for smw.property module

	@since 1.0

	@licence GNU GPL v2+
	@author Tobias Oetterer
]]

local testframework = require 'Module:TestFramework'

-- Tests
local tests = {
	{ name = 'subobject (no argument)', func = mw.smw.subobject,
		args = { '' },
		expect = { true }
	},
	{ name = 'subobject (matching input types to property types)', func = mw.smw.subobject,
		args = { { 'Has text=test', ['Is bool'] = 'true' } },
		expect = { true }
	},

	{ name = 'subobject (supplying a wrong input type to property type)', func = mw.smw.subobject,
		args = { { 'Has type=test', ['Is bool'] = 'true' } },
		expect = {
			{
				false,
				error = mw.message.new('smw-datavalue-property-restricted-declarative-use', 'Has type'):inLanguage('en'):plain()
				--error = mw.message.new('smw_unknowntype'):inLanguage('en'):plain()
				-- should be error = mw.message.new('smw_unknowntype'):inLanguage(mw.getContentLanguage()):plain()
			}
		}
	},
	{ name = 'subobject (assigning data to non existing property)', func = mw.smw.subobject,
		args = { { 'Has text=test', ['Is bool'] = 'true' , 'foo=test' } },
		expect = { true }
	},
	{ name = 'subobject (no argument, supplying id)', func = mw.smw.subobject,
		args = { {}, '0123456780_teststringAsId' },
		expect = { true }
	},
	{ name = 'subobject (matching input types to property types, supplying id)', func = mw.smw.subobject,
		args = { { 'Has text=test', ['Is bool'] = 'true' }, '0123456780_teststringAsId' },
		expect = { true }
	},

	{ name = 'subobject (supplying a wrong input type to property type, supplying id)', func = mw.smw.subobject,
		args = { { 'Has type=test', ['Allows value'] = 'test' }, '0123456780_teststringAsId' },
		expect = {
			{
				false,
				error = '&lt;ul&gt;&lt;li&gt;' .. mw.message.new('smw-datavalue-property-restricted-declarative-use', 'Has type'):inLanguage('en'):plain()
					.. '&lt;/li&gt; &lt;!--br--&gt;&lt;li&gt;' .. mw.message.new('smw-datavalue-property-restricted-declarative-use', 'Allows value'):inLanguage('en'):plain() .. '&lt;/li&gt;&lt;/ul&gt;'
				-- error = mw.message.new('smw_unknowntype'):inLanguage('en'):plain()
				-- should be error = mw.message.new('smw_unknowntype'):inLanguage(mw.getContentLanguage()):plain()
			}
		}
	},
	{ name = 'subobject (assigning data to non existing property, supplying id)', func = mw.smw.subobject,
		args = { { 'Has text=test', ['Is bool'] = 'true' , 'foo=test' }, '0123456780_teststringAsId' },
		expect = { true }
	},
}

return testframework.getTestProvider( tests )
