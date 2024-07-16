<?php

namespace SMW\Scribunto\Tests\Unit;

/**
 * @group Test
 * @group Database
 * @group semantic-scribunto
 * @covers \SMW\Scribunto\ScribuntoLuaLibrary
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Tobias Oetterer
 */
class ScribuntoLuaLibrarySubobjectTest extends ScribuntoLuaEngineTestBase {

	/**
	 * Lua test module
	 * @var string
	 */
	protected static $moduleName = self::class;

	/**
	 * ScribuntoLuaEngineTestBase::getTestModules
	 */
	public function getTestModules() {
		return parent::getTestModules() + [
			self::$moduleName => __DIR__ . '/' . 'mw.smw.subobject.tests.lua',
		];
	}

	/**
	 * Tests method subobject through assertions based upon
	 * dataProvider {@see dataProviderSubobjectTest}
	 *
	 * @dataProvider dataProviderSubobjectTest
	 * @param array $arguments arguments passed to function
	 * @param mixed $expected expected return value
	 */
	public function testSubobject( $arguments, $expected ) {
		$this->assertEquals(
			$expected,
			call_user_func_array( [ $this->getScribuntoLuaLibrary(), 'subobject' ], $arguments )
		);
	}

	/**
	 * Data provider for {@see testSubobject}
	 *
	 * @see testSubobject
	 *
	 * @return array
	 */
	public function dataProviderSubobjectTest()
	{
		$provider = [
			[
				[ [] ],
				[ 1 => true ]
			],
			[
				[ null ],
				[ 1 => true ]
			],
			[
				[ '' ],
				[ 1 => true ]
			],
			[
				[ [ 'has text=test', 'Is bool=true' ] ],
				[ 1 => true ]
			],
			[
				[ [ 'has type=test', 'Is bool=true' ] ],
				[ [ 1 => false, 'error' => wfMessage('smw-datavalue-property-restricted-declarative-use', 'Has type')->inLanguage('en')->plain() ] ]
			],
			[
				[ [ 'has text=test', 'Is bool=true','1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' ] ],
				[ 1 => true ]
			],
			[
				[ [], '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
			[
				[ null, '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
			[
				[ '', '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
			[
				[ [ 'has text=test', 'Is bool=true' ], '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
			[
				[ [ 'has type=test', 'Allows value=test' ], '01234567890_testStringAsId' ],
				[ [ 1 => false, 'error' => '&lt;ul&gt;&lt;li&gt;' . wfMessage('smw-datavalue-property-restricted-declarative-use', 'Has type')->inLanguage('en')->plain() . '&lt;/li&gt; &lt;!--br--&gt;&lt;li&gt;' . wfMessage('smw-datavalue-property-restricted-declarative-use', 'Allows value')->inLanguage('en')->plain() . '&lt;/li&gt;&lt;/ul&gt;' ] ]
			],
			[
				[ [ 'has text=test', 'Is bool=true' => 'test','1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' ], '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
		];

		return $provider;
	}
}
