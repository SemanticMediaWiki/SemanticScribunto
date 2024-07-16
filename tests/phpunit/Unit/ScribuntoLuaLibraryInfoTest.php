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
class ScribuntoLuaLibraryInfoTest extends ScribuntoLuaEngineTestBase {

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
			self::$moduleName => __DIR__ . '/' . 'mw.smw.info.tests.lua',
		];
	}


	/**
	 * Tests method info
	 *
	 * @return void
	 */
	public function testInfo() {
		$this->assertEmpty(
			$this->getScribuntoLuaLibrary()->info( null )
		);
		$this->assertEmpty(
			$this->getScribuntoLuaLibrary()->info( '' )
		);
		$this->assertIsString(
			$this->getScribuntoLuaLibrary()->info( 'Test info text' )[0]
		);
		$this->assertStringStartsWith(
			'<span',
			$this->getScribuntoLuaLibrary()->info( 'Test info text' )[0]
		);
		$this->assertStringEndsWith(
			'</span>',
			$this->getScribuntoLuaLibrary()->info( 'Test info text' )[0]
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*info">.*>Test info text<.*</span>$~', $this->getScribuntoLuaLibrary()->info( 'Test info text' )[0])
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*warning">.*>Test info text<.*</span>$~', $this->getScribuntoLuaLibrary()->info( 'Test info text', 'warning' )[0])
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*error">.*>Test info text<.*</span>$~', $this->getScribuntoLuaLibrary()->info( 'Test info text', 'error' )[0])
		);
		$this->assertEquals(
			1,
			preg_match('~^<span class=.*<span class="[^"]*info">.*>Test info text<.*</span>$~', $this->getScribuntoLuaLibrary()->info( 'Test info text', 'invalid' )[0])
		);
	}
}
