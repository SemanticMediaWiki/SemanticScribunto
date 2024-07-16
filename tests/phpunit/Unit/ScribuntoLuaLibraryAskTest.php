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
class ScribuntoLuaLibraryAskTest extends ScribuntoLuaEngineTestBase {

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
			self::$moduleName => __DIR__ . '/' . 'mw.smw.ask.tests.lua',
		];
	}


	/**
	 * Tests method info
	 *
	 * @return void
	 */
	public function testAsk() {
		$this->assertEmpty(
			$this->getScribuntoLuaLibrary()->ask( null )[0]
		);
		$this->assertEmpty(
			$this->getScribuntoLuaLibrary()->ask( '' )[0]
		);
#		$this->assertArrayHasKey(
#			0,
#			$this->getScribuntoLuaLibrary()->ask( '[[Modification date::2024-07-16]]|?Modification date|limit=2|mainlabel=main' )
#		);
		// invalid query
		$this->assertNull(
			$this->getScribuntoLuaLibrary()->ask( '?Modification date|limit=2|mainlabel=main' )[0]
		);
	}
}
