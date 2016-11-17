<?php

namespace SMW\Scribunto;

use Scribunto_LuaLibraryBase;
use SMW\DIProperty;
use FauxRequest;
use ApiMain;

use SMWQueryProcessor as QueryProcessor;
use SMW\ApplicationFactory;
use SMW\ParameterProcessorFactory;
use SMW\ParserFunctionFactory;
use SMWOutputs;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class ScribuntoLuaLibrary extends Scribunto_LuaLibraryBase {

	/**
	 * This is the name of the key for error messages
	 *
	 * @var string
	 * @since 1.0
	 */
	const SMW_ERROR_FIELD='error';

	/**
	 * @since 1.0
	 */
	public function register() {

		$lib = array(
			'getPropertyType' => array( $this, 'getPropertyType' ),
			'getQueryResult'  => array( $this, 'getQueryResult' ),
			'info'            => array( $this, 'info' ),
			'set'             => array( $this, 'set' ),
		);

		$this->getEngine()->registerInterface( __DIR__ . '/' . 'mw.smw.lua', $lib, array() );
	}

	/**
	 * Returns query results in for of the standard API return format
	 *
	 * @since 1.0
	 *
	 * @param string $argString
	 *
	 * @return array
	 */
	public function getQueryResult( $argString = null ) {

		$rawParameters = preg_split( "/(?<=[^\|])\|(?=[^\|])/", $argString );

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

		$queryResult = ApplicationFactory::getInstance()->getStore()->getQueryResult( $query )->toArray();
		if(!empty($queryResult["results"])) {
		    $queryResult["results"] = array_combine(range(1, count($queryResult["results"])), array_values($queryResult["results"]));
		}
		return array( $queryResult );
	}

	/**
	 * Returns property type
	 *
	 * @since 1.0
	 *
	 * @param string $propertyName
	 *
	 * @return array
	 */
	public function getPropertyType( $propertyName = null ) {

		$this->checkType( 'getPropertyType', 1, $propertyName, 'string' );
		$propertyName = trim( $propertyName );

		if ( $propertyName === '' ) {
			return array( null );
		}

		$property = DIProperty::newFromUserLabel( $propertyName );

		if ( $property === null ) {
			return array( null );
		}

		return array( $property->findPropertyTypeID() );
	}

	/**
	 * This mirrors the functionality of the parser function #info and makes it available to lua.
	 *
	 * @since 1.0
	 *
	 * @param string $text text to show inside the info popup
	 * @param string $icon identifier for the icon to use
	 *
	 * @uses \SMW\ParserFunctionFactory::__construct
	 *
	 * @return string[]
	 */
	public function info( $text, $icon = 'info' ) {

		$parser = $this->getEngine()->getParser();

		// do some parameter processing
		if ( ! trim( $text ) || ! is_string( $text ) ) {
			// no info-text present, or wrong type. abort
			return null;
		}

		// check if icon is set and valid
		if ( !is_string( $icon ) || !in_array( $icon, [ 'note', 'warning' ] ) ) {
			$icon = 'info';
		}

		// the actual info message is easy to create:
		$result = smwfEncodeMessages(
			array( $text ),
			$icon,
			' <!--br-->',
			false // No escaping.
		);

		// to have all necessary data committed to output, use SMWOutputs::commitToParser()
		SMWOutputs::commitToParser( $parser );

		return array( $this->extractResultString( $result ) );
	}

	/**
	 * This mirrors the functionality of the parser function #set and makes it available in lua.
	 *
	 * @since 1.0
	 *
	 * @param string|array $parameters parameters passed from lua, string or array depending on call
	 *
	 * @uses \SMW\ParserFunctionFactory::__construct, ParameterProcessorFactory::newFromArray
	 *
	 * @return null|array|array[]
	 */
	public function set( $parameters ) {

		$parser = $this->getEngine()->getParser();

		// if we have no arguments, do nothing
		if ( !sizeof( $arguments = $this->processLuaArguments( $parameters ) ) ) {
			return null;
		}

		// prepare setParserFunction object
		$parserFunctionFactory = new ParserFunctionFactory( $parser );
		$setParserFunction = $parserFunctionFactory->newSetParserFunction( $parser );

		// call parse on setParserFunction
		$parserFunctionCallResult = $setParserFunction->parse(
			ParameterProcessorFactory::newFromArray( $arguments )
		);

		// get usable result
		$result = $this->extractResultString( $parserFunctionCallResult );

		if ( strlen( $result ) ) {
			// if result a non empty string, assume an error message
			return array( [ 1 => false, self::SMW_ERROR_FIELD => preg_replace( '/<[^>]+>/', '', $result ) ] );
		} else {
			// on success, return true
			return array( 1 => true );
		}
	}

	/**
	 * Takes a result returned from a parser function call and prepares it to be used as parsed string.
	 *
	 * @since 1.0
	 *
	 * @param string|array $parserFunctionResult
	 *
	 * @return string
	 */
	private function extractResultString( $parserFunctionResult ) {

		// parser function call can return string or array
		if ( is_array( $parserFunctionResult ) ) {
			$result = $parserFunctionResult[0];
			$noParse = isset($parserFunctionResult['noparse']) ? $parserFunctionResult['noparse'] : true;
		} else {
			$result = $parserFunctionResult;
			$noParse = true;
		}

		if ( ! $noParse ) {
			$result = $this->getEngine()->getParser()->recursiveTagParseFully( $result );
		}
		return trim( $result );
	}

	/**
	 * Takes the $arguments passed from lua and pre-processes them: make sure, we have a sequence array (not associative)
	 *
	 * @since 1.0
	 *
	 * @param string|array $arguments
	 *
	 * @return array
	 */
	private function processLuaArguments( $arguments ) {

		// make sure, we have an array of parameters
		if ( !is_array( $arguments ) ) {
			$arguments = array( $arguments );
		}

		// if $arguments were supplied as key => value pair (aka associative array), we rectify this here
		$processedArguments = array();
		foreach ( $arguments as $key => $value ) {
			if ( !is_int( $key ) && !preg_match( '/[0-9]+/', $key ) ) {
				$value = (string) $key . '=' . (string) $value;
			}
			if ( $value ) {
				// only add, when value is set. could be empty, if lua function was called with no parameter or empty string
				$processedArguments[] = $value;
			}
		}

		return $processedArguments;
	}
}
