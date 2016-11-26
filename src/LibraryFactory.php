<?php

namespace SMW\Scribunto;

use SMWQueryProcessor as QueryProcessor;
use SMW\Store;
use SMW\ApplicationFactory;
use SMW\ParameterProcessorFactory;
use Parser;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class LibraryFactory {

	/**
	 *@var Store
	 */
	private $store;

	/**
	 * @since 1.0
	 *
	 * @param Store $store
	 */
	public function __construct( Store $store ) {
		$this->store = $store;
	}

	/**
	 * @since 1.0
	 *
	 * @param string|array $rawParameters
	 *
	 * @return QueryResult
	 */
	public function newQueryResultFrom( $rawParameters ) {

		list( $queryString, $parameters, $printouts ) = QueryProcessor::getComponentsFromFunctionParams(
			$rawParameters,
			false
		);

		QueryProcessor::addThisPrintout( $printouts, $parameters );

		$query = QueryProcessor::createQuery(
			$queryString,
			QueryProcessor::getProcessedParams( $parameters, $printouts ),
			QueryProcessor::SPECIAL_PAGE,
			'',
			$printouts
		);

		return $this->store->getQueryResult( $query );
	}

	/**
	 * @since 1.0
	 *
	 * @param array $arguments
	 *
	 * @return ParserParameterProcessor
	 */
	public function newParserParameterProcessorFrom( $arguments ) {
		return ParameterProcessorFactory::newFromArray( $arguments );
	}

	/**
	 * @since 1.0
	 *
	 * @param Parser $parser
	 *
	 * @return SetParserFunction
	 */
	public function newSetParserFunction( Parser $parser ) {
		return ApplicationFactory::getInstance()->newParserFunctionFactory( $parser )->newSetParserFunction( $parser );
	}

	/**
	 * @since 1.0
	 *
	 * @param Parser $parser
	 *
	 * @return SubobjectParserFunction
	 */
	public function newSubobjectParserFunction( Parser $parser ) {
		return ApplicationFactory::getInstance()->newParserFunctionFactory( $parser )->newSubobjectParserFunction( $parser );
	}

}
