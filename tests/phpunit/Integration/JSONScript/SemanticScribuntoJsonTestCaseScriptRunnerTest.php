<?php

namespace SMW\Scribunto\Integration\JSONScript;

use SMW\DIWikiPage;
use SMW\Scribunto\HookRegistry;
use SMW\Tests\JsonTestCaseFileHandler;
use SMW\Tests\JsonTestCaseScriptRunner;
use SMW\Tests\LightweightJsonTestCaseScriptRunner;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticMediaWiki/tree/master/tests#write-integration-tests-using-json-script
 *
 * `JsonTestCaseScriptRunner` provisioned by SMW is a base class allowing to use a JSON
 * format to create test definitions with the objective to compose "real" content
 * and test integration with MediaWiki, Semantic MediaWiki, and Scribunto.
 *
 * The focus is on describing test definitions with its content and specify assertions
 * to control the expected base line.
 *
 * `JsonTestCaseScriptRunner` will handle the tearDown process and ensures that no test
 * data are leaked into a production system but requires an active DB connection.
 *
 * @group semantic-scribunto
 * @group medium
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class SemanticScribuntoJsonTestCaseScriptRunnerTest extends LightweightJsonTestCaseScriptRunner {

	/**
	 * @var HookRegistry
	 */
	private $hookRegistry;

	protected function setUp() {
		parent::setUp();

		$this->hookRegistry = new HookRegistry();

		$this->hookRegistry->clear();
		$this->hookRegistry->register();
	}

	/**
	 * @see JsonTestCaseScriptRunner::getRequiredJsonTestCaseMinVersion
	 * @return string
	 */
	protected function getRequiredJsonTestCaseMinVersion() {
		return '1';
	}

	/**
	 * @see JsonTestCaseScriptRunner::getTestCaseLocation
	 * @return string
	 */
	protected function getTestCaseLocation() {
		return __DIR__ . '/TestCases';
	}

	/**
	 * @see JsonTestCaseScriptRunner::getPermittedSettings
	 */
	protected function getPermittedSettings() {
		parent::getPermittedSettings();

		return [
			'smwgNamespacesWithSemanticLinks',
			'smwgPageSpecialProperties',
			'smwgMaxNonExpNumber',
			'wgLanguageCode',
			'wgContLang',
			'wgLang'
		];
	}

}
