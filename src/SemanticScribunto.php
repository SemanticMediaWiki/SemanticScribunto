<?php

/**
 * @see https://github.com/SemanticMediaWiki/SemanticScribunto/
 *
 * @defgroup SemanticScribunto Semantic Scribunto
 */

namespace SMW\Scribunto;

/**
 * @codeCoverageIgnore
 */
class SemanticScribunto
{

	/**
	 * @since 1.0
	 */
	public static function initExtension( $credits = [] ) {

		// See https://phabricator.wikimedia.org/T151136
		define( 'SMW_SCRIBUNTO_VERSION', isset( $credits['version'] ) ? $credits['version'] : 'UNKNOWN' );
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
					'extension requires <a href="https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a> ' .
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
	}
}
