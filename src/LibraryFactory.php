<?php

namespace SMW\Scribunto;

use SMW\ParserFunctions\SetParserFunction;
use SMW\ParserFunctions\SubobjectParserFunction;
use SMW\ParserParameterProcessor;
use SMW\Query\QueryContext;
use SMWQueryProcessor as QueryProcessor;
use SMWQuery as Query;
use SMW\Query\QueryResult;
use SMW\Store;
use SMW\Services\ServicesFactory as ApplicationFactory;
use SMW\ParameterProcessorFactory;
use \Parser;

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
	 * Creates a new QueryResult from passed arguments,
	 * utilizing the {@see SMWQueryProcessor}
	 *
	 * @since 1.0
	 *
	 * @param array $rawParameters
	 *
	 * @return QueryResult
	 */
	public function newQueryResultFrom( array $rawParameters ): QueryResult {

		list( $queryString, $parameters, $printouts ) = QueryProcessor::getComponentsFromFunctionParams(
			$rawParameters,
			false
		);

		QueryProcessor::addThisPrintout( $printouts, $parameters );

		$query = QueryProcessor::createQuery(
			$queryString,
			QueryProcessor::getProcessedParams( $parameters, $printouts ),
			QueryContext::SPECIAL_PAGE,
			'',
			$printouts
		);

		if ( defined( 'SMWQuery::PROC_CONTEXT' ) ) {
			$query->setOption( Query::PROC_CONTEXT, 'SSC.LibraryFactory' );
		}

		return $this->store->getQueryResult( $query );
	}

	/**
	 * @since 1.0
	 *
	 * @param QueryResult|string $queryResult
	 *
	 * @return LuaAskResultProcessor
	 */
	public function newLuaAskResultProcessor( QueryResult $queryResult ): LuaAskResultProcessor {
		return new LuaAskResultProcessor( $queryResult );
	}

	/**
	 * Creates a new ParserParameterProcessor from passed arguments
	 *
	 * @since 1.0
	 *
	 * @param array $arguments
	 *
	 * @return ParserParameterProcessor
	 */
	public function newParserParameterProcessorFrom( $arguments ): ParserParameterProcessor {
		return ParameterProcessorFactory::newFromArray( $arguments );
	}

	/**
	 * Creates a new SetParserFunction utilizing a Parser
	 *
	 * @since 1.0
	 *
	 * @param Parser $parser
	 *
	 * @return SetParserFunction
	 */
	public function newSetParserFunction( Parser $parser ): SetParserFunction {
		return ApplicationFactory::getInstance()->newParserFunctionFactory()->newSetParserFunction( $parser );
	}

	/**
	 * Creates a new SubobjectParserFunction utilizing a Parser
	 *
	 * @since 1.0
	 *
	 * @param Parser $parser
	 *
	 * @return SubobjectParserFunction
	 */
	public function newSubobjectParserFunction( Parser $parser ): SubobjectParserFunction {
		return ApplicationFactory::getInstance()->newParserFunctionFactory()->newSubobjectParserFunction( $parser );
	}
}
