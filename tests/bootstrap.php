<?php

if ( PHP_SAPI !== 'cli' ) {
	die( 'Not an entry point' );
}

error_reporting( E_ALL | E_STRICT );
date_default_timezone_set( 'UTC' );
ini_set( 'display_errors', 1 );

if ( !defined( 'SMW_PHPUNIT_AUTOLOADER_FILE' ) || !is_readable( SMW_PHPUNIT_AUTOLOADER_FILE ) ) {
	die( "\nThe Semantic MediaWiki test autoloader is not available" );
}

if ( !defined( 'SMW_SCRIBUNTO_VERSION' ) ) {
	die( "\nSemantic Scribunto is not available or loaded, please check your Composer or LocalSettings.\n" );
}

$lua = 'n/a';

if ( method_exists( 'Scribunto_LuaStandaloneInterpreter', 'getLuaVersion' ) ) {
	$lua = Scribunto_LuaStandaloneInterpreter::getLuaVersion( [ 'luaPath' => null ] );
}

print sprintf( "\n%-20s%s\n", "Semantic Scribunto: ", SMW_SCRIBUNTO_VERSION );
print sprintf( "%-20s%s\n", "Lua version: ", $lua );

// What is the Scribunto version?? Who knows ..., it doesn't have a version number

$autoLoader = require SMW_PHPUNIT_AUTOLOADER_FILE;
$autoLoader->addPsr4( 'SMW\\Scribunto\\Tests\\', __DIR__ . '/phpunit/Unit' );
$autoLoader->addPsr4( 'SMW\\Scribunto\\Tests\\Integration\\', __DIR__ . '/phpunit/Integration' );
unset( $autoLoader );
