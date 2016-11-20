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

if ( defined( 'SMW_SCRIBUNTO_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

SemanticScribunto::initExtension();

$GLOBALS['wgExtensionFunctions'][] = function() {
	SemanticScribunto::onExtensionFunction();
};

/**
 * @codeCoverageIgnore
 */
class SemanticScribunto {

	/**
	 * @since 1.0
	 */
	public static function initExtension() {

		if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
			include_once __DIR__ . '/vendor/autoload.php';
		}

		define( 'SMW_SCRIBUNTO_VERSION', '1.0.0-alpha' );

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
		$GLOBALS['wgMessagesDirs']['SemanticScribunto'] = __DIR__ . '/i18n';
	}

	/**
	 * @since 1.0
	 */
	public static function checkRequirements() {

		if ( version_compare( $GLOBALS[ 'wgVersion' ], '1.26', 'lt' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> is only compatible with MediaWiki 1.26 or above. You need to upgrade MediaWiki first.' );
		}

		// Using the constant as indicator to avoid class_exists
		if ( !defined( 'CONTENT_MODEL_SCRIBUNTO' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> requires <a href="https://www.mediawiki.org/wiki/Extension:Scribunto">Scribunto</a>, please enable or install the extension first.' );
		}

		if ( !defined( 'SMW_VERSION' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> requires <a href="https://github.com/SemanticMediaWiki/SemanticMediaWiki/">Semantic MediaWiki</a>, please enable or install the extension first.' );
		}
	}

	/**
	 * @since 1.0
	 */
	public static function onExtensionFunction() {

		// Check requirements after LocalSetting.php has been processed
		self::checkRequirements();

		$hookRegistry = new HookRegistry();
		$hookRegistry->register();
	}

	/**
	 * @since 1.0
	 *
	 * @return string|null
	 */
	public static function getVersion() {
		return SMW_SCRIBUNTO_VERSION;
	}

}
