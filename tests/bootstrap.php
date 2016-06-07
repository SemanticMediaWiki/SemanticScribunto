<?php

if ( PHP_SAPI !== 'cli' ) {
	die( 'Not an entry point' );
}

if ( !is_readable( $autoloaderClassPath = __DIR__ . '/../../SemanticMediaWiki/tests/autoloader.php' ) ) {
	die( 'The SemanticMediaWiki test autoloader is not available' );
}

print sprintf( "\n%-20s%s\n", "Semantic Scribunto: ", SMW_SCRIBUNTO_VERSION );

$autoLoader = require $autoloaderClassPath;
$autoLoader->addPsr4( 'SMW\\Scribunto\\Tests\\', __DIR__ . '/phpunit/Unit' );
$autoLoader->addPsr4( 'SMW\\Scribunto\\Tests\\Integration\\', __DIR__ . '/phpunit/Integration' );
unset( $autoLoader );
