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
 * @author mwjames
 */
class ScribuntoLuaLibraryPropertyTest extends ScribuntoLuaEngineTestBase {

	/**
	 * Lua test module
	 * @var string
	 */
	protected static $moduleName = 'SMW\Scribunto\Tests\ScribuntoLuaLibraryPropertyTest';

	/**
	 * ScribuntoLuaEngineTestBase::getTestModules
	 */
	public function getTestModules() {
		return parent::getTestModules() + [
			'SMW\Scribunto\Tests\ScribuntoLuaLibraryPropertyTest' => __DIR__ . '/' . 'mw.smw.property.tests.lua',
		];
	}

	/**
	 * Tests method getPropertyType
	 *
	 * @return void
	 */
	public function testGetPropertyType() {
		$this->assertEmpty(
			$this->getScribuntoLuaLibrary()->getPropertyType( '' )[0]
		);
		$this->assertEquals(
			'_dat',
			$this->getScribuntoLuaLibrary()->getPropertyType( 'Modification date' )[0]
		);
		$this->assertEquals(
			'_wpg',
			$this->getScribuntoLuaLibrary()->getPropertyType( 'Foo' )[0]
		);
	}
}
