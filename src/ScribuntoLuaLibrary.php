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
	public function getQueryResult( $queryString = null ) {

		$this->checkType( 'getQueryResult', 1, $queryString, 'string' );
		$queryString = trim( $queryString );

		if ( $queryString === '' ) {
			return array( null );
		}

		$params = new FauxRequest(
			array(
				'action' => 'ask',
				'query'  => $queryString
			)
		);

		$api = new ApiMain( $params );
		$api->execute();
		$data = $api->getResult()->getResultData();

		return $data === null ? array( null ) : $data;
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
