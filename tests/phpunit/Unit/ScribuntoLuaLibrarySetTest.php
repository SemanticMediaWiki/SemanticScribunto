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
class ScribuntoLuaLibrarySetTest extends ScribuntoLuaEngineTestBase {

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
			self::$moduleName => __DIR__ . '/' . 'mw.smw.set.tests.lua',
		];
	}

	/**
	 * Tests method set through assertions based upon
	 * dataProvider {@see \SMW\Scribunto\Tests\ScribuntoLuaLibraryTest::dataProviderSetTest}
	 *
	 * @dataProvider dataProviderSetTest
	 * @param array $arguments arguments passed to function
	 * @param mixed $expected expected return value
	 */
	public function testSet( $arguments, $expected) {
		$this->assertEquals(
			$expected,
			$this->getScribuntoLuaLibrary()->set($arguments)
		);
	}

	/**
	 * Data provider for {@see testSet}
	 *
	 * @see testSet
	 *
	 * @return array
	 */
	public function dataProviderSetTest() {
		return [
			[
				null,
				[ 1 => true ]
			],
			[
				'',
				[ 1 => true ]
			],
			[
				[ ],
				[ 1 => true ]
			],
			[
				[ '' ],
				[ 1 => true ]
			],
			[
				[ 'has text=test' ],
				[ 1 => true ]
			],
			[
				[ 'has type=test' ],
				[ [ 1 => false, 'error' => wfMessage('smw-datavalue-property-restricted-declarative-use', 'Has type')->inLanguage('en')->plain() ] ]
			],
			[
				[ '1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' ],
				[ 1 => true ]
			],
			[
				[ '1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test', 'foo' => 'bar' ],
				[ 1 => true ]
			]
		];
	}
}
