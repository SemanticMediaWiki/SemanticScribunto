<?php

namespace SMW\Scribunto\Tests;

/**
 * @covers \SMW\Scribunto\ScribuntoLuaLibrary
 * @group semantic-scribunto
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class ScribuntoLuaLibraryResultsTest extends ScribuntoLuaEngineTestBase {

	/**
	 * Lua test module
	 * @var string
	 */
	protected static $moduleName = 'SMW\Scribunto\Tests\ScribuntoLuaLibraryResultsTest';

	/**
	 * ScribuntoLuaEngineTestBase::getTestModules
	 */
	public function getTestModules() {
		return parent::getTestModules() + array(
			'SMW\Scribunto\Tests\ScribuntoLuaLibraryResultsTest' => __DIR__ . '/' . 'mw.ext.smw.results.tests.lua',
		);
	}

}