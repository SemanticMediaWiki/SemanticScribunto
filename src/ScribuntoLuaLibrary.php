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
			'getQueryResult'  => array( $this, 'getQueryResult' ),
			'getPropertyType' => array( $this, 'getPropertyType' ),
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
	 * @param string	$text	text to show inside the info popup
	 * @param string    $icon   identifier for the icon to use
	 *
	 * @uses \SMW\ParserFunctionFactory::__construct
	 *
	 * @return string[]
	 */
	public function info( $text, $icon='info' )
	{
		$parser = $this->getEngine()->getParser();

		# do some parameter processing
		if ( !trim($text) || ! is_string($text) ) {
			# no info-text present, or wrong type abort
			return null;
		}
		$infoText = trim($text);

		# check if icon is set and valid
		if ( !is_string($icon) || !in_array($icon, ['note', 'warning']) ) {
			$icon = 'info';
		}

		# the actual info message is easy to create:
		$result = smwfEncodeMessages(
			array( $infoText ),
			$icon,
			' <!--br-->',
			false // No escaping.
		);

		# to have all necessary data commited to output, use SMWOutputs::commitToParser()
		SMWOutputs::commitToParser( $parser );

		# result may be an array with probably 'noparse' set to false. check to be sure
		$noParse = ( is_array($result) && isset($result['noparse']) ) ? $result['noparse'] : true;
		$result = is_array($result) ? $result[0] : $result;

		if ( ! $noParse ) {
			$result = $parser->recursiveTagParseFully( $result );
		}

		return array( $result );
	}

	/**
	 * This mirrors the functionality of the parser function #set and makes it available in lua.
	 *
	 * @param string|array	$parameters	parameters passed from lua, string or array depending on call
	 *
	 * @uses \SMW\ParserFunctionFactory::__construct, ParameterProcessorFactory::newFromArray
	 *
	 * @return null|array|array[]
	 */
	public function set( $parameters )
	{
		$parser = $this->getEngine()->getParser();

		# make sure, we have an array of parameters
		if ( !is_array($parameters) ) {
			$parameters = array($parameters);
		}

		# if $parameters were supplied as key => value pair (aka associative array), we have to rectify this here
		$argumentsToParserFunction = array();
		foreach ( $parameters as $key => $value ) {
			if ( !is_int($key) && !preg_match('/[0-9]+/', $key) ) {
				$value = $key . '=' . $value;
			}
			if ( $value ) {
				# only add, when value is set. could be empty, if set was called with no parameter or empty string
				$argumentsToParserFunction[] = $value;
			}
		}

		# if we have no arguments, do nothing
		if ( !sizeof($argumentsToParserFunction) ) {
			return null;
		}

		# prepare setParserFunction object
		$parserFunctionFactory = new ParserFunctionFactory( $parser );
		$setParserFunction = $parserFunctionFactory->newSetParserFunction( $parser );

		# call parse on setParserFunction
		$parserFunctionCallResult = $setParserFunction->parse(
			ParameterProcessorFactory::newFromArray( $argumentsToParserFunction )
		);

		# get result
		if ( is_array($parserFunctionCallResult) ) {
			$result = $parserFunctionCallResult[0];
			$noParse = isset($parserFunctionCallResult['noparse']) ? $parserFunctionCallResult['noparse'] : true;
		} else {
			$result = $parserFunctionCallResult;
			$noParse = true;
		}

		if ( ! $noParse ) {
			$result = $parser->recursiveTagParseFully( $result );
		}
		$result = trim($result);

		if ( strlen ($result) ) {
			# if result a non empty string, assume an error message
			return array( [ 1 => false, self::SMW_ERROR_FIELD => preg_replace('/<[^>]+>/', '', $result) ] );
		} else {
			# on success, return true
			return array( 1 => true );
		}
	}
}
