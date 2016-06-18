<?php

if ( PHP_SAPI !== 'cli' ) {
	die( 'Not an entry point' );
}

if ( !is_readable( $autoloaderClassPath = __DIR__ . '/../../SemanticMediaWiki/tests/autoloader.php' ) ) {
	die( 'The SemanticMediaWiki test autoloader is not available' );
}

if ( ( $version = SemanticScribunto::getVersion() ) === null ) {
	die( "\SemanticScribunto is not loaded, please check your LocalSettings or Composer settings.\n" );
}

print sprintf( "\n%-20s%s\n", "Semantic Scribunto: ", $version );

$autoLoader = require $autoloaderClassPath;
$autoLoader->addPsr4( 'SMW\\Scribunto\\Tests\\', __DIR__ . '/phpunit/Unit' );
$autoLoader->addPsr4( 'SMW\\Scribunto\\Tests\\Integration\\', __DIR__ . '/phpunit/Integration' );
unset( $autoLoader );
