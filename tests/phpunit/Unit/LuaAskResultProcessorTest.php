<?php

namespace SMW\Scribunto\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SMW\DataValues\StringValue;
use SMW\Scribunto\LuaAskResultProcessor;
use SMW\Query\QueryResult;
use SMW\Query\PrintRequest;
use SMW\Query\Result\ResultArray;
use SMWNumberValue;

/**
 * @covers LuaAskResultProcessor
 * @group semantic-scribunto
 * @group Database
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Tobias Oetterer
 */
class LuaAskResultProcessorTest extends TestCase {

	/**
	 * Holds a mock of a query result for this test
	 *
	 * @var QueryResult
	 */
	private $queryResult;

	/**
	 * Set-up method prepares a mock {@see QueryResult}
	 */
	protected function setUp(): void {

		parent::setUp();

		$this->queryResult = $this->createMock( QueryResult::class );

		$this->queryResult->expects( $this->any() )
			->method( 'getNext' )
			->will( $this->onConsecutiveCalls( [ $this->constructResultArray() ], false ) );

		$this->queryResult->expects( $this->any() )
			->method( 'getCount' )
			->will( $this->returnValue( 1 ) );
	}

	/**
	 * Test, if the constructor works
	 *
	 * @see LuaAskResultProcessor::__construct
	 *
	 * @return void
	 */
	public function testCanConstruct() {

		$this->assertInstanceOf(
			LuaAskResultProcessor::class,
			new LuaAskResultProcessor(
				$this->queryResult
			)
		);
	}

	/**
	 * Tests the conversion of a {@see QueryResult} in a lua table
	 *
	 * @return void
	 * @see LuaAskResultProcessor::getProcessedResult
	 *
	 */
	public function testGetProcessedResult() {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$result = $instance->getProcessedResult();

		$this->assertIsArray(
			$result
		);

		$this->assertEquals(
			1,
			count( $result )
		);
	}

	/**
	 * Tests the data extraction from a result row
	 *
	 * @return void
	 * @see LuaAskResultProcessor::getDataFromQueryResultRow
	 *
	 */
	public function testGetDataFromQueryResultRow() {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$resultRow = [ $this->constructResultArray() ];

		$result = $instance->getDataFromQueryResultRow( $resultRow );

		$this->assertIsArray(
			$result
		);

		$this->assertEquals( 1, count( $result ) );
	}

	/**
	 * Tests the retrieval of a key (string label or numeric index) from
	 * a print request
	 *
	 * @see LuaAskResultProcessor::getKeyFromPrintRequest
	 *
	 * @return void
	 */
	public function testGetKeyFromPrintRequest() {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$printRequest = $this->constructPrintRequest();

		$printRequest->expects( $this->any() )
			->method( 'getLabel' )
			->will( $this->returnValue( 'label' ) );

		$printRequest->expects( $this->any() )
			->method( 'getText' )
			->will( $this->returnValue( 'label' ) );

		$printRequest2 = $this->constructPrintRequest();

		$printRequest2->expects( $this->any() )
			->method( 'getLabel' )
			->will( $this->returnValue( '' ) );

		$this->assertIsString(
			$instance->getKeyFromPrintRequest( $printRequest )
		);

		$this->assertEquals(
			'label',
			$instance->getKeyFromPrintRequest( $printRequest )
		);

		$this->assertIsInt(
			$instance->getKeyFromPrintRequest( $printRequest2 )
		);

		$this->assertGreaterThan(
			0,
			$instance->getKeyFromPrintRequest( $printRequest2 )
		);
	}

	/**
	 * Tests the extraction of data from a SMWResultArray
	 *
	 * @return void
	 * @see LuaAskResultProcessor::getDataFromResultArray
	 *
	 */
	public function testGetDataFromResultArray() {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$resultArray = $this->constructResultArray();

		$this->assertIsArray(
			$instance->getDataFromResultArray( $resultArray )
		);
	}

	/**
	 * Tests data value extraction. Uses data provider
	 * @dataProvider dataProviderGetValueFromDataValueTest
	 *
	 * @param string $class name of data value class
	 * @param string $type data value type
	 * @param string $expects return value type
	 *
	 * @return void
	 * @see          LuaAskResultProcessor::getValueFromDataValue
	 *
	 */
	public function testGetValueFromDataValue( $class, $type, $expects ) {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$dataValue = $this->getMockBuilder( '\\' . $class )
			->setConstructorArgs( [ $type ] )
			->getMock();

		$dataValue->expects( $this->any() )
			->method( 'getTypeID' )
			->will( $this->returnValue( $type ) );

		$this->$expects(
			$instance->getValueFromDataValue( $dataValue )
		);
	}

	/**
	 * Tests the conversion of a list of result values into a value, usable in lua.
	 * Uses data provider {@see dataProviderExtractLuaDataFromDVData}
	 * @dataProvider dataProviderExtractLuaDataFromDVData
	 *
	 * @param mixed $expects expected return value
	 * @param array $input input for method
	 *
	 * @return void
	 * @see          LuaAskResultProcessor::extractLuaDataFromDVData
	 *
	 */
	public function testExtractLuaDataFromDVData( $expects, $input ) {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$this->assertEquals(
			$expects,
			$instance->extractLuaDataFromDVData( $input )
		);
	}

	/**
	 * Tests the generation of a numeric index key
	 *
	 * @return void
	 * @see LuaAskResultProcessor::getNumericIndex
	 *
	 */
	public function testGetNumericIndex() {

		$instance = new LuaAskResultProcessor( $this->queryResult );

		$this->assertIsInt( $instance->getNumericIndex() );

		$this->assertGreaterThan(
			1,
			$instance->getNumericIndex()
		);
	}

	/**
	 * Data provider for {@see testgetValueFromDataValue}
	 *
	 * @see testgetValueFromDataValue
	 *
	 * @return array
	 */
	public function dataProviderGetValueFromDataValueTest() {

		return [
			[ SMWNumberValue::class, '_num', 'assertIsInt' ],
			[ \SMWWikiPageValue::class, '_wpg', 'assertNull' ],
			[ StringValue::class, '_boo', 'assertIsBool' ],
			[ \SMWTimeValue::class, '_dat', 'assertNull' ],
		];
	}

	/**
	 * Data provider for {@see testExtractLuaDataFromDVData}
	 *
	 * @see testExtractLuaDataFromDVData
	 *
	 * @return array
	 */
	public function dataProviderExtractLuaDataFromDVData() {
		return [
			[ null, [] ],
			[ 42, [ 42 ] ],
			[ [ 'foo', 'bar' ], [ 'foo', 'bar' ] ]
		];
	}

	/**
	 * Constructs a mock {@see ResultArray}
	 */
	private function constructResultArray() {

		$resultArray = $this->getMockBuilder( ResultArray::class )
			->disableOriginalConstructor()
			->getMock();

		$resultArray->expects( $this->any() )
			->method( 'getPrintRequest' )
			->will( $this->returnValue(
				$this->constructPrintRequest()
			) );

		$resultArray->expects( $this->any() )
			->method( 'getNextDataValue' )
			->will( $this->onConsecutiveCalls(
				$this->constructSMWNumberValue(),
				false
			) );

		return $resultArray;
	}

	/**
	 * Constructs a mock {@see \SMW\Query\PrintRequest}
	 */
	private function constructPrintRequest() {

		$printRequest = $this->getMockBuilder( PrintRequest::class )
			->disableOriginalConstructor()
			->getMock();

		return $printRequest;
	}


	/**
	 * Constructs a mock {@see \SMWNumberValue}
	 */
	private function constructSMWNumberValue() {

		$printRequest = $this->getMockBuilder( SMWNumberValue::class )
			->setConstructorArgs( [ '_num' ] )
			->getMock();

		return $printRequest;
	}
}
