<?php

namespace SMW\Scribunto\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SMW\ParserFunctions\SetParserFunction;
use SMW\Query\QueryResult;
use SMW\Scribunto\LibraryFactory;

/**
 * @covers \SMW\Scribunto\LibraryFactory
 * @group semantic-scribunto
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class LibraryFactoryTest extends TestCase {

	private $store;
	private $parser;

	protected function setUp(): void {
		$language = $this->getMockBuilder( '\Language' )
			->disableOriginalConstructor()
			->getMock();

		$queryResult = $this->getMockBuilder( '\SMWQueryResult' )
			->disableOriginalConstructor()
			->getMock();

		$this->store = $this->getMockBuilder( '\SMW\Store' )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->store->expects( $this->any() )
			->method( 'getQueryResult' )
			->willReturn( $queryResult );

		$stripState = $this->createMock( \StripState::class );
		$this->parser = $this->createMock( \Parser::class );

		$this->parser->expects( $this->any() )
			->method( 'getStripState' )
			->willReturn( $stripState );

		$this->parser->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( \Title::newFromText( 'Foo' ) );

		$this->parser->expects( $this->any() )
			->method( 'getOutput' )
			->willReturn( new \ParserOutput() );

		$this->parser->expects( $this->any() )
			->method( 'getTargetLanguage' )
			->willReturn( $language );
	}

	public function testCanConstruct() {
		$this->assertInstanceOf(
			LibraryFactory::class,
			new LibraryFactory( $this->store )
		);
	}

	public function testCanConstructQueryResult() {
		$instance = new LibraryFactory(
			$this->store
		);

		$this->assertInstanceOf(
			QueryResult::class,
			$instance->newQueryResultFrom( [ '[[Foo::Bar]]' ] )
		);
	}

	public function testCanConstructParserParameterProcessor() {
		$instance = new LibraryFactory(
			$this->store
		);

		$this->assertInstanceOf(
			'\SMW\ParserParameterProcessor',
			$instance->newParserParameterProcessorFrom( [ '' ] )
		);
	}

	public function testCanConstructSetParserFunction() {
		$instance = new LibraryFactory(
			$this->store
		);

		$this->assertInstanceOf(
			SetParserFunction::class,
			$instance->newSetParserFunction( $this->parser )
		);
	}

	public function testCanConstructSubobjectParserFunction() {
		$instance = new LibraryFactory(
			$this->store
		);

		$this->assertInstanceOf(
			'\SMW\ParserFunctions\SubobjectParserFunction',
			$instance->newSubobjectParserFunction( $this->parser )
		);
	}

	public function testCanConstructLuaAskResultProcessor() {
		$queryResult = $this->getMockBuilder( '\SMWQueryResult' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new LibraryFactory(
			$this->store
		);

		$this->assertInstanceOf(
			'\SMW\Scribunto\LuaAskResultProcessor',
			$instance->newLuaAskResultProcessor( $queryResult )
		);
	}

}
