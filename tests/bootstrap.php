<?php

if ( PHP_SAPI !== 'cli' ) {
	die( 'Not an entry point' );
}

error_reporting( E_ALL | E_STRICT );
date_default_timezone_set( 'UTC' );
ini_set( 'display_errors', 1 );

#if ( !defined( 'SMW_PHPUNIT_AUTOLOADER_FILE' ) || !is_readable( SMW_PHPUNIT_AUTOLOADER_FILE ) ) {
#	die( "\nThe Semantic MediaWiki test autoloader is not available" );
#}

if ( !defined( 'SMW_SCRIBUNTO_VERSION' ) ) {
	die( "\nSemantic Scribunto is not available or loaded, please check your Composer or LocalSettings.\n" );
}

$lua = 'n/a';

$luaInterpreterClass = '\MediaWiki\Extension\Scribunto\Engines\LuaStandalone\LuaStandaloneInterpreter';
if ( class_exists( $luaInterpreterClass ) && method_exists( $luaInterpreterClass, 'getLuaVersion' ) ) {
	$lua = $luaInterpreterClass::getLuaVersion( [ 'luaPath' => null ] );
}

print sprintf( "\n%-20s%s\n", "Semantic Scribunto: ", SMW_SCRIBUNTO_VERSION );
print sprintf( "%-20s%s\n", "Lua version: ", $lua );

// What is the Scribunto version?? Who knows ..., it doesn't have a version number

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false
	? getenv( 'MW_INSTALL_PATH' )
	: __DIR__ . '/../../..';

if ( is_readable( $path = $basePath . '/vendor/autoload.php' ) ) {
	$autoloadType = "MediaWiki vendor autoloader";
} elseif ( is_readable( $path = __DIR__ . '/../vendor/autoload.php' ) ) {
	$autoloadType = "Extension vendor autoloader";
} else {
	die( 'To run the test suite it is required that packages are installed using Composer.' );
}

/**
 * getting the autoloader and registering test classes
 */
/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require $path;

$autoloader->addPsr4( 'SMW\\Scribunto\\Tests\\', __DIR__ . '/phpunit' );
unset( $autoloader );
