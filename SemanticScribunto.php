<?php

use SMW\Scribunto\HookRegistry;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticScribunto/
 *
 * @defgroup SemanticScribunto Semantic Scribunto
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is part of the Semantic Scribunto extension, it is not a valid entry point.' );
}

if ( version_compare( $GLOBALS[ 'wgVersion' ], '1.24', 'lt' ) ) {
	die( '<b>Error:</b> This version of <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> is only compatible with MediaWiki 1.24 or above. You need to upgrade MediaWiki first.' );
}

if ( defined( 'SMW_SCRIBUNTO_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

define( 'SMW_SCRIBUNTO_VERSION', '1.0.0-alpha' );

/**
 * @codeCoverageIgnore
 */
call_user_func( function () {

	// Register extension info
	$GLOBALS['wgExtensionCredits']['semantic'][] = array(
		'path'           => __FILE__,
		'name'           => 'Semantic Scribunto',
		'author'         => array( 'James Hong Kong' ),
		'url'            => 'https://github.com/SemanticMediaWiki/SemanticScribunto/',
		'descriptionmsg' => 'smw-scribunto-desc',
		'version'        => SMW_SCRIBUNTO_VERSION,
		'license-name'   => 'GPL-2.0+',
	);

	// MW 1.26+
	if ( !function_exists( 'wfGlobalCacheKey' ) ) {
		function wfGlobalCacheKey( /*...*/ ) {
			$args = func_get_args();
			$key = 'global:' . implode( ':', $args );
			return strtr( $key, ' ', '_' );
		}
	}

	// Register message files
	$GLOBALS['wgMessagesDirs']['smw-scribunto'] = __DIR__ . '/i18n';

	// Finalize extension setup
	$GLOBALS['wgExtensionFunctions'][] = function() {

		$hookRegistry = new HookRegistry();
		$hookRegistry->register();
	};

} );
