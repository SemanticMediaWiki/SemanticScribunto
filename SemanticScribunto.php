<?php

use SMW\Scribunto\HookRegistry;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticScribunto/
 *
 * @defgroup SemanticScribunto Semantic Scribunto
 */
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
	}

	/**
	 * @since 1.0
	 */
	public static function initExtension( $credits = [] ) {

		// See https://phabricator.wikimedia.org/T151136
		define( 'SMW_SCRIBUNTO_VERSION', isset( $credits['version'] ) ? $credits['version'] : 'UNKNOWN' );

		// Register message files
		$GLOBALS['wgMessagesDirs']['SemanticScribunto'] = __DIR__ . '/i18n';
	}

	/**
	 * @since 1.0
	 */
	public static function onExtensionFunction() {

		if ( !defined( 'SMW_VERSION' ) ) {
			if ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
				die( "\nThe 'Semantic Scribunto' extension requires 'Semantic MediaWiki' to be installed and enabled.\n" );
			} else {
				die(
					'<b>Error:</b> The <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> ' .
					'extension requires <a href="https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a> '.
					'to be installed and enabled.<br />'
				);
			}
		}

		// Using the constant as indicator to avoid class_exists
		if ( !defined( 'CONTENT_MODEL_SCRIBUNTO' ) ) {
			if ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
				die( "\nThe 'Semantic Scribunto' extension requires the 'Scribunto' extension to be installed and enabled.\n" );
			} else {
				die(
					'<b>Error:</b> <a href="https://github.com/SemanticMediaWiki/SemanticScribunto/">Semantic Scribunto</a> ' .
					'requires <a href="https://www.mediawiki.org/wiki/Extension:Scribunto">Scribunto</a>. Please install and ' .
					'enable the extension first.<br />'
				);
			}
		}

		$hookRegistry = new HookRegistry();
		$hookRegistry->register();
	}

}
