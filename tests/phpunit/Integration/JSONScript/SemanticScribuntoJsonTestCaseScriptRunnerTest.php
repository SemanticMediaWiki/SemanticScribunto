<?php

namespace SMW\Scribunto\Integration\JSONScript;

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

	protected function setUp() {
		parent::setUp();

		$this->testEnvironment->tearDown();
	}

	/**
	 * @see JSONScriptTestCaseRunner::getRequiredJsonTestCaseMinVersion
	 * @return string
	 */
	protected function getRequiredJsonTestCaseMinVersion() {
		return '1';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getTestCaseLocation
	 * @return string
	 */
	protected function getTestCaseLocation() {
		return __DIR__ . '/TestCases';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getPermittedSettings
	 */
	protected function getPermittedSettings() {
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
