<?php

namespace SMW\Scribunto;

/**
 * Class LuaAskResultProcessor
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author Tobias Oetterer
 *
 * @package SMW\Scribunto
 */
class LuaAskResultProcessor {

	/**
	 * Holds all possible representations of "true" in this smw instance
	 *
	 * @var array
	 */
	private $msgTrue;

	/**
	 * This counter serves as fallback, if no label for printout is specified
	 *
	 * @var int
	 */
	private $numericIndex;

	/**
	 * Holds the query result for this object
	 *
	 * @var \SMWQueryResult
	 */
	private $queryResult;

	/**
	 * LuaAskResultProcessor constructor.
	 *
	 * @param \SMWQueryResult $queryResult
	 */
	public function __construct( \SMWQueryResult $queryResult ) {

		$this->queryResult = $queryResult;
		$this->msgTrue = explode( ',', wfMessage( 'smw_true_words' )->text() . ',true,t,yes,y' );
		$this->numericIndex = 1;
	}

	/**
	 * Extracts the data in {@see $queryResult} and returns it as a table
	 * usable in lua context
	 *
	 * @uses getDataFromQueryResultRow
	 *
	 * @return array|null
	 */
	public function getQueryResultAsTable() {

		$result = null;

		if ( $this->queryResult->getCount() ) {

			$result = array();

			while ( $resultRow = $this->queryResult->getNext() ) {
				$result[] = $this->getDataFromQueryResultRow( $resultRow );
			}
		}

		return $result;
	}

	/**
	 * Extracts the data of a single result row in the {@see $queryResult}
	 * and returns it as a table usable in lua context
	 *
	 * @param array $resultRow result row as an array of {@see SMWResultArray} objects
	 *
	 * @uses getDataFromSMWResultArray
	 *
	 * @return array
	 */
	public function getDataFromQueryResultRow( array $resultRow ) {

		$rowData = array();

		/** @var \SMWResultArray $resultArray */
		foreach ( $resultRow as $resultArray ) {

			// get key and data
			list ( $key, $data ) = $this->getDataFromSMWResultArray( $resultArray );

			$rowData[$key] = $data;
		}

		return $rowData;
	}

	/**
	 * Extracts the data of a single printRequest / query field
	 *
	 * @param \SMWResultArray $resultArray
	 *
	 * @uses getKeyFromPrintRequest, getValueFromSMWDataValue, extractLuaDataFromDVData
	 *
	 * @return array array ( int|string, array )
	 */
	public function getDataFromSMWResultArray( \SMWResultArray $resultArray ) {

		// first, extract the key (label), if any
		$key = $this->getKeyFromPrintRequest( $resultArray->getPrintRequest() );

		$resultArrayData = array();

		// then get all data that is stored in this printRequest / query field
		/** @var \SMWDataValue $dataValue */
		while ( ( $dataValue = $resultArray->getNextDataValue() ) !== false ) {

			$resultArrayData[] = $this->getValueFromSMWDataValue( $dataValue );
		}

		$resultArrayData = $this->extractLuaDataFromDVData( $resultArrayData );

		return array( $key, $resultArrayData );
	}

	/**
	 * Takes an smw query print request and tries to retrieve the label
	 * falls back to [@see getNumericIndex} if non found
	 *
	 * @param \SMW\Query\PrintRequest $printRequest
	 *
	 * @uses getNumericIndex
	 *
	 * @return int|string
	 */
	public function getKeyFromPrintRequest( \SMW\Query\PrintRequest $printRequest ) {

		if ( $printRequest->getLabel() !== '' ) {
			return $printRequest->getText( SMW_OUTPUT_WIKI );
		} else {
			return $this->getNumericIndex();
		}
	}

	/**
	 * Extracts the data of a single value of the current printRequest / query field
	 * The value is formatted according to the type of the property
	 *
	 * @param \SMWDataValue $dataValue
	 *
	 * @return mixed
	 */
	public function getValueFromSMWDataValue( \SMWDataValue $dataValue ) {

		switch ( $dataValue->getTypeID() ) {
			case '_boo':
				// boolean value found, convert it
				$value = in_array( strtolower( $dataValue->getShortText( SMW_OUTPUT_WIKI ) ), $this->msgTrue );
				break;
			case '_num':
				// number value found
				/** @var \SMWNumberValue $dataValue */
				$value = $dataValue->getNumber();
				$value = ( $value == (int) $value ) ? intval( $value ) : $value;
				break;
			default:
				# FIXME ignores parameter link=none|subject
				$value = $dataValue->getShortText( SMW_OUTPUT_WIKI, new \Linker() );
		}

		return $value;
	}


	/**
	 * Takes an array of data fields and returns either null, a single value or a lua table.
	 *
	 * @param array $resultArrayData
	 *
	 * @return mixed
	 */
	public function extractLuaDataFromDVData( $resultArrayData ) {

		if ( empty( $resultArrayData ) ) {
			// this key has no value(s). set to null
			$resultArrayData = null;
		} elseif ( count( $resultArrayData ) == 1 ) {
			// there was only one semantic value found. reduce the array to this value
			$resultArrayData = array_shift( $resultArrayData );
		} else {
			// $key has multiple values. keep the array, but un-shift it (remember: lua array counting starts with 1)
			array_unshift( $resultArrayData, null );
		}

		return $resultArrayData;
	}

	/**
	 * Returns current numeric index (and increases it afterwards)
	 *
	 * @uses $numericIndex
	 *
	 * @return int
	 */
	public function getNumericIndex() {
		return $this->numericIndex++;
	}
}