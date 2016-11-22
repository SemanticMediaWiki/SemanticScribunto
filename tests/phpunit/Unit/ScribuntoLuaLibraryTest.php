<?php

namespace SMW\Scribunto\Tests;

use SMW\Scribunto\ScribuntoLuaLibrary;

/**
 * @covers \SMW\Scribunto\ScribuntoLuaLibrary
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author oetterer
 */
class ScribuntoLuaLibraryTest extends \Scribunto_LuaEngineTestBase {

	/**
	 * @var \SMW\Scribunto\ScribuntoLuaLibrary
	 */
	protected $scribuntoLuaLibrary;

	/**
	 * Lua test module
	 * @var string
	 */
	protected static $moduleName = self::class;

	/**
	 * ScribuntoLuaEngineTestBase::getTestModules
	 */
	public function getTestModules() {
		return parent::getTestModules() + array(
			self::$moduleName => __DIR__ . '/' . 'mw.smw.tests.lua',
		);
	}

	protected function setUp() {
		parent::setUp();

		/** @noinspection PhpParamsInspection */
		$this->scribuntoLuaLibrary = new ScribuntoLuaLibrary(
			$this->getEngine()
		);
	}

	public function testCanConstruct() {
		$this->assertInstanceOf(
			'\SMW\Scribunto\ScribuntoLuaLibrary',
			$this->scribuntoLuaLibrary
		);
	}

	/**
	 * Test, if all the necessary methods exists. Uses data provider {@see dataProviderFunctionTest}
	 * @dataProvider dataProviderFunctionTest
	 *
	 * @param string $method name of method to check
	 *
	 * @used \SMW\Scribunto\Tests\ScribuntoLuaLibraryTest::dataProviderFunctionTest
	 *
	 * @return void
	 */
	public function testMethodsExist( $method ) {
		$this->assertTrue(
			method_exists( $this->scribuntoLuaLibrary, $method ),
			'Class \SMW\Scribunto\ScribuntoLuaLibrary has method \'' . $method . '()\' missing!'
		);
	}

	/**
	 * Tests method getPropertyType
	 *
	 * @return void
	 */
	public function testGetPropertyType() {
		$this->assertEmpty(
			$this->scribuntoLuaLibrary->getPropertyType( '' )[0]
		);
		$this->assertEquals(
			'_dat',
			$this->scribuntoLuaLibrary->getPropertyType( 'Modification date' )[0]
		);
		$this->assertEquals(
			'_wpg',
			$this->scribuntoLuaLibrary->getPropertyType( 'Foo' )[0]
		);
	}

	/**
	 * Tests method getQueryResult
	 *
	 * @return void
	 */
	public function testGetQueryResult() {
		$this->assertArrayHasKey(
			'meta',
			$this->scribuntoLuaLibrary->getQueryResult()[0]
		);
		$this->assertArrayHasKey(
			'count',
			$this->scribuntoLuaLibrary->getQueryResult()[0]['meta']
		);
		$this->assertEquals(
			0,
			$this->scribuntoLuaLibrary->getQueryResult()[0]['meta']['count']
		);
		$this->assertArrayHasKey(
			'printrequests',
			$this->scribuntoLuaLibrary->getQueryResult( '[[Modification date::+]]|?Modification date|limit=0|mainlabel=-' )[0]
		);
		$this->assertArrayHasKey(
			0,
			$this->scribuntoLuaLibrary->getQueryResult( '[[Modification date::+]]|?Modification date|limit=0|mainlabel=-' )[0]['printrequests']
		);
		$this->assertArrayHasKey(
			'label',
			$this->scribuntoLuaLibrary->getQueryResult( '[[Modification date::+]]|?Modification date|limit=0|mainlabel=-' )[0]['printrequests'][0]
		);
		$this->assertEquals(
			'Modification date',
			$this->scribuntoLuaLibrary->getQueryResult( '[[Modification date::+]]|?Modification date|limit=0|mainlabel=-' )[0]['printrequests'][0]['label']
		);
	}

	/**
	 * Tests method info
	 *
	 * @return void
	 */
	public function testInfo() {
		$this->assertEmpty(
			$this->scribuntoLuaLibrary->info( null )
		);
		$this->assertEmpty(
			$this->scribuntoLuaLibrary->info( '' )
		);
		$this->assertInternalType(
			'string',
			$this->scribuntoLuaLibrary->info( 'Test info text' )[0]
		);
		$this->assertStringStartsWith(
			'<span',
			$this->scribuntoLuaLibrary->info( 'Test info text' )[0]
		);
		$this->assertStringEndsWith(
			'</span>',
			$this->scribuntoLuaLibrary->info( 'Test info text' )[0]
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*info">.*>Test info text<.*</span>$~', $this->scribuntoLuaLibrary->info( 'Test info text' )[0])
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*warning">.*>Test info text<.*</span>$~', $this->scribuntoLuaLibrary->info( 'Test info text', 'warning' )[0])
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*info">.*>Test info text<.*</span>$~', $this->scribuntoLuaLibrary->info( 'Test info text', 'invalid' )[0])
		);
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
			$this->scribuntoLuaLibrary->set($arguments)
		);
	}

	/**
	 * Tests method subobject through assertions based upon
	 * dataProvider {@see \SMW\Scribunto\Tests\ScribuntoLuaLibraryTest::dataProviderSubobjectTest}
	 *
	 * @dataProvider dataProviderSubobjectTest
	 * @param array $arguments arguments passed to function
	 * @param mixed $expected expected return value
	 */
	public function testSubobject( $arguments, $expected ) {
		$this->assertEquals(
			$expected,
			call_user_func_array( array( $this->scribuntoLuaLibrary, 'subobject' ), $arguments )
		);
	}

	/**
	 * Data provider for {@see testFunctions}
	 *
	 * @see testFunctions
	 *
	 * @return array
	 */
	public function dataProviderFunctionTest() {

		return [
			[ 'getPropertyType' ],
			[ 'getQueryResult' ],
			[ 'info' ],
			[ 'set' ],
			[ 'subobject' ]
		];
	}

	/**
	 * Data provider for {@see testSet}
	 *
	 * @see testSet
	 *
	 * @return array
	 */
	public function dataProviderSetTest() {
		$provider = array(
			[
				[],
				null
			],
			[
				[ 'has type=page' ],
				[ 1 => true ]
			],
			[
				[ 'has type=test' ],
				[ array( 1 => false, 'error' => wfMessage('smw_unknowntype')->inLanguage('en')->plain() ) ]
			],
			[
				[ '1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' ],
				[ 1 => true ]
			]
		);

		return $provider;
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
		$provider = array(
			[
				[ [] ],
				null
			],
			[
				[ null ],
				null
			],
			[
				[ '' ],
				null
			],
			[
				[ [ 'has type=page', 'Allows value=test' ] ],
				[ 1 => true ]
			],
			[
				[ [ 'has type=test', 'Allows value=test' ] ],
				[ array( 1 => false, 'error' => wfMessage('smw_unknowntype')->inLanguage('en')->plain() ) ]
			],
			[
				[ [ 'has type=page', 'Allows value=test','1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' ] ],
				[ 1 => true ]
			],
			[
				[ [], '01234567890_testStringAsId' ],
				null
			],
			[
				[ null, '01234567890_testStringAsId' ],
				null
			],
			[
				[ '', '01234567890_testStringAsId' ],
				null
			],
			[
				[ [ 'has type=page', 'Allows value=test' ], '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
			[
				[ [ 'has type=test', 'Allows value=test' ], '01234567890_testStringAsId' ],
				[ array( 1 => false, 'error' => wfMessage('smw_unknowntype')->inLanguage('en')->plain() ) ]
			],
			[
				[ [ 'has type=page', 'Allows value=test','1215623e790d918773db943232068a93b21c9f1419cb85666c6558e87f5b7d47=test' ], '01234567890_testStringAsId' ],
				[ 1 => true ]
			],
		);

		return $provider;
	}
}