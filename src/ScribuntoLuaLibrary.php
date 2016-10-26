<?php

namespace SMW\Scribunto;

use Scribunto_LuaLibraryBase;
use SMW\DIProperty;
use FauxRequest;
use ApiMain;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class ScribuntoLuaLibrary extends Scribunto_LuaLibraryBase {

	/**
	 * @since 1.0
	 */
	public function register() {

		$lib = array(
			'getQueryResult'  => array( $this, 'getQueryResult' ),
			'getPropertyType' => array( $this, 'getPropertyType' ),
		);

		$this->getEngine()->registerInterface( __DIR__ . '/' . 'mw.ext.smw.lua', $lib, array() );
	}

	/**
	 * Returns query results in for of the standard API return format
	 *
	 * @since 1.0
	 *
	 * @param string $queryString
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
		    QueryProcessor::INLINE_QUERY,
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

}
