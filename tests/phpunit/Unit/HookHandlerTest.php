<?php

namespace SMW\Scribunto\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SMW\Scribunto\HooksHandler;
use SMW\Scribunto\ScribuntoLuaLibrary;

/**
 * @covers  \SMW\Scribunto\HooksHandler
 *
 * @ingroup Test
 *
 * @group semantic-scribunto
 *
 * @license GPL-2.0-or-later
 *
 * @since   2.3
 * @author  Tobias Oetterer
 */
class HooksHandlerTest extends TestCase {

	/**
	 * @throws \ConfigException
	 */
	public function testOnScribuntoExternalLibraries() {
		$libraries = [];
		$this->assertTrue(
			HooksHandler::onScribuntoExternalLibraries( '', $libraries )
		);
		$this->assertEquals(
			[],
			$libraries
		);
		$this->assertTrue(
			HooksHandler::onScribuntoExternalLibraries( 'lua', $libraries )
		);
		$this->assertArrayHasKey(
			'mw.smw',
			$libraries
		);
		$this->assertEquals(
			ScribuntoLuaLibrary::class,
			$libraries['mw.smw']
		);
	}
}
