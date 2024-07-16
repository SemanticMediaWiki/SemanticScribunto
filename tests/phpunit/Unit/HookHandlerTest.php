<?php

namespace SMW\Scribunto\Tests\Unit;

use SMW\Scribunto\HooksHandler;
use PHPUnit\Framework\TestCase;
use SMW\Scribunto\ScribuntoLuaLibrary;

/**
 * @covers  \SMW\Scribunto\HooksHandler
 *
 * @ingroup Test
 *
 * @group semantic-scribunto
 *
 * @license GNU GPL v2+
 *
 * @since   2.3
 * @author  Tobias Oetterer
 */
class HooksHandlerTest extends TestCase
{


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
