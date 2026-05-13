<?php

namespace SMW\Scribunto\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use SMW\Scribunto\ScribuntoLuaLibrary;

/**
 * Characterisation test pinning down the current behaviour of the private
 * {@see ScribuntoLuaLibrary::convertArrayToLuaTable} helper before it is
 * refactored. The test invokes the private method via reflection so the
 * production class needs no visibility changes.
 *
 * @covers \SMW\Scribunto\ScribuntoLuaLibrary::convertArrayToLuaTable
 * @group semantic-scribunto
 *
 * @license GPL-2.0-or-later
 * @since 7.0.0
 */
class ConvertArrayToLuaTableTest extends TestCase {

	private function invoke( mixed $input ): mixed {
		$instance = ( new ReflectionClass( ScribuntoLuaLibrary::class ) )
			->newInstanceWithoutConstructor();
		$method = new ReflectionMethod( ScribuntoLuaLibrary::class, 'convertArrayToLuaTable' );
		$method->setAccessible( true );
		return $method->invoke( $instance, $input );
	}

	public function testNonArrayPassthrough(): void {
		$this->assertSame( 'hello', $this->invoke( 'hello' ) );
		$this->assertSame( 42, $this->invoke( 42 ) );
		$this->assertNull( $this->invoke( null ) );
	}

	public function testEmptyArray(): void {
		$this->assertSame( [], $this->invoke( [] ) );
	}

	public function testZeroIndexedListShiftsToOneIndexed(): void {
		$this->assertSame(
			[ 1 => 'a', 2 => 'b', 3 => 'c' ],
			$this->invoke( [ 'a', 'b', 'c' ] )
		);
	}

	public function testAssociativeKeysUnchanged(): void {
		$this->assertSame(
			[ 'foo' => 'a', 'bar' => 'b' ],
			$this->invoke( [ 'foo' => 'a', 'bar' => 'b' ] )
		);
	}

	public function testMixedKeys(): void {
		// Integer keys shift by 1; string keys are left untouched by array_unshift().
		$this->assertSame(
			[ 1 => 'a', 'foo' => 'b', 2 => 'c' ],
			$this->invoke( [ 0 => 'a', 'foo' => 'b', 1 => 'c' ] )
		);
	}

	public function testNested(): void {
		$expected = [ 1 => [ 1 => 'inner-a', 2 => 'inner-b' ] ];
		$this->assertSame( $expected, $this->invoke( [ [ 'inner-a', 'inner-b' ] ] ) );
	}

	public function testDeeplyNested(): void {
		$input = [ [ [ 'leaf' ] ] ];
		$expected = [ 1 => [ 1 => [ 1 => 'leaf' ] ] ];
		$this->assertSame( $expected, $this->invoke( $input ) );
	}

}
