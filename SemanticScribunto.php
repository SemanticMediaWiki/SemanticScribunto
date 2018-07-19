<?php

use SMW\Scribunto\HookRegistry;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticScribunto/
 *
 * @defgroup SemanticScribunto Semantic Scribunto
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is part of the Semantic Scribunto extension. It is not a valid entry point.' );
}

if ( defined( 'SMW_SCRIBUNTO_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

SemanticScribunto::load();

/**
 * @codeCoverageIgnore
 */
class SemanticScribunto {

	/**
	 * @since 1.0
	 *
	 * @note It is expected that this function is loaded before LocalSettings.php
	 * to ensure that settings and global functions are available by the time
	 * the extension is activated.
	 */
	public static function load() {

		if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
			include_once __DIR__ . '/vendor/autoload.php';
		}

		// In case extension.json is being used, the the succeeding steps will
		// be handled by the ExtensionRegistry
		self::initExtension();

		$GLOBALS['wgExtensionFunctions'][] = function() {
			self::onExtensionFunction();
		};
	}

	/**
	 * @since 1.0
	 */
	public static function initExtension() {

		define( 'SMW_SCRIBUNTO_VERSION', '1.2.0' );

		// Register extension info
		$GLOBALS['wgExtensionCredits']['semantic'][] = [
			'path'           => __FILE__,
			'name'           => 'Semantic Scribunto',
			'author'         => [
				'James Hong Kong',
				'[https://www.semantic-mediawiki.org/wiki/User:Oetterer Tobias Oetterer]',
			],
			'url'            => 'https://github.com/SemanticMediaWiki/SemanticScribunto/',
			'descriptionmsg' => 'smw-scribunto-desc',
			'version'        => SMW_SCRIBUNTO_VERSION,
			'license-name'   => 'GPL-2.0-or-later'
		];

		// Register message files
		$GLOBALS['wgMessagesDirs']['SemanticScribunto'] = __DIR__ . '/i18n';
	}

	/**
	 * @since 1.0
	 */
	public static function doCheckRequirements() {

		if ( version_compare( $GLOBALS[ 'wgVersion' ], '1.27', 'lt' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> is only compatible with MediaWiki 1.26 or above. You need to upgrade MediaWiki first.' );
		}

		// Using the constant as indicator to avoid class_exists
		if ( !defined( 'CONTENT_MODEL_SCRIBUNTO' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> requires <a href="https://www.mediawiki.org/wiki/Extension:Scribunto">Scribunto</a>. Please enable or install the extension first.' );
		}

		if ( !defined( 'SMW_VERSION' ) ) {
			die( '<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> requires <a href="https://github.com/SemanticMediaWiki/SemanticMediaWiki/">Semantic MediaWiki</a>. Please enable or install the extension first.' );
		}
	}

	/**
	 * @since 1.0
	 */
	public static function onExtensionFunction() {

		// Check requirements after LocalSetting.php has been processed
		self::doCheckRequirements();

		$hookRegistry = new HookRegistry();
		$hookRegistry->register();
	}

	/**
	 * @since 1.0
	 *
	 * @param string|null $dependency
	 *
	 * @return string|null
	 */
	public static function getVersion( $dependency = null ) {

		if ( $dependency === null && defined( 'SMW_SCRIBUNTO_VERSION' ) ) {
			return SMW_SCRIBUNTO_VERSION;
		}

		if ( $dependency === 'SMW' && defined( 'SMW_VERSION' ) ) {
			return SMW_VERSION;
		}

		if ( $dependency === 'Lua' && method_exists( 'Scribunto_LuaStandaloneInterpreter', 'getLuaVersion' ) ) {
			return Scribunto_LuaStandaloneInterpreter::getLuaVersion( [ 'luaPath' => null ] );
		}

		return null;
	}

}
