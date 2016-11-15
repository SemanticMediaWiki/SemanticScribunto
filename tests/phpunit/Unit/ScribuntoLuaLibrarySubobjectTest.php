<?php

namespace SMW\Scribunto\Tests;

/**
 * @covers \SMW\Scribunto\ScribuntoLuaLibrary
 * @group semantic-scribunto
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author oetterer
 */
class ScribuntoLuaLibrarySubobjectTest extends ScribuntoLuaEngineTestBase {

	/**
	 * Lua test module
	 * @var string
	 */
	protected static $moduleName = 'SMW\Scribunto\Tests\ScribuntoLuaLibrarySubobjectTest';

	/**
	 * ScribuntoLuaEngineTestBase::getTestModules
	 */
	public function getTestModules() {
		return parent::getTestModules() + array(
			'SMW\Scribunto\Tests\ScribuntoLuaLibrarySubobjectTest' => __DIR__ . '/' . 'mw.smw.subobject.tests.lua',
		);
	}
}