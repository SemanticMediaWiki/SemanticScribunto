<?php

namespace SMW\Scribunto\Integration\JSONScript;

use MediaWiki\MediaWikiServices;
use SMW\Tests\JSONScriptServicesTestCaseRunner;
use SMW\Tests\JSONScriptTestCaseRunner;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticMediaWiki/tree/master/tests#write-integration-tests-using-json-script
 *
 * `JSONScriptTestCaseRunner` provisioned by SMW is a base class allowing to use a JSON
 * format to create test definitions with the objective to compose "real" content
 * and test integration with MediaWiki, Semantic MediaWiki, and Scribunto.
 *
 * The focus is on describing test definitions with its content and specify assertions
 * to control the expected base line.
 *
 * `JSONScriptTestCaseRunner` will handle the tearDown process and ensures that no test
 * data are leaked into a production system but requires an active DB connection.
 *
 * @group semantic-scribunto
 * @group SMWExtension
 * @group medium
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class SemanticScribuntoJsonTestCaseScriptRunnerTest extends JSONScriptServicesTestCaseRunner {

	protected function setUp(): void {
		parent::setUp();

		// SMW's MwHooksHandler::deregisterListedHooks (called from
		// JSONScriptTestCaseRunner::setUp) issues HookContainer::clear() against
		// every hook in its list, including ParserFirstCallInit. clear() wipes
		// ALL handlers for the hook — not just SMW's — and the subsequent
		// invokeHooksFromRegistry() only restores SMW's. Without this re-register,
		// Scribunto's #invoke parser function is missing during the test parse
		// and {{#invoke:...}} renders literally.
		//
		// Read the handler spec dynamically from Scribunto's extension.json so
		// the test stays compatible across MW versions where Scribunto's
		// constructor service list changes.
		$services = MediaWikiServices::getInstance();
		$scribuntoExtJsonPath = $services->getMainConfig()->get( 'ExtensionDirectory' ) . '/Scribunto/extension.json';
		$scribuntoConfig = json_decode( file_get_contents( $scribuntoExtJsonPath ), true );
		$pfciHandlerName = $scribuntoConfig['Hooks']['ParserFirstCallInit'];
		$handlerSpec = $scribuntoConfig['HookHandlers'][$pfciHandlerName];
		$handler = $services->getObjectFactory()->createObject( $handlerSpec, [ 'allowClassName' => true ] );
		$services->getHookContainer()->register( 'ParserFirstCallInit', [ $handler, 'onParserFirstCallInit' ] );
	}

	/**
	 * @see JSONScriptTestCaseRunner::getRequiredJsonTestCaseMinVersion
	 * @return string
	 */
	protected function getRequiredJsonTestCaseMinVersion(): string {
		return '1';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getTestCaseLocation
	 * @return string
	 */
	protected function getTestCaseLocation(): string {
		return __DIR__ . '/TestCases';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getPermittedSettings
	 */
	protected function getPermittedSettings(): array {
		$settings = parent::getPermittedSettings();

		return array_merge( $settings, [
			'smwgNamespacesWithSemanticLinks',
			'smwgPageSpecialProperties',
			'smwgMaxNonExpNumber',
			'wgLanguageCode',
			'wgContLang',
			'wgLang'
		] );
	}

}
