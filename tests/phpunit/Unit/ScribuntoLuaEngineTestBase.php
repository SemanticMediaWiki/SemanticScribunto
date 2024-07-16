<?php

namespace SMW\Scribunto\Tests\Unit;

use PHPUnit\Framework\TestResult;
use SMW\Scribunto\ScribuntoLuaLibrary;
use Scribunto_LuaEngineTestBase;

/**
 * Encapsulation of the Scribunto_LuaEngineTestBase class
 *
 * @group Test
 * @group Database
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
abstract class ScribuntoLuaEngineTestBase extends Scribunto_LuaEngineTestBase
{
	/**
	 * @var ScribuntoLuaLibrary
	 */
	private $scribuntoLuaLibrary;

	protected function setUp(): void {
		parent::setUp();

		$this->scribuntoLuaLibrary = new ScribuntoLuaLibrary(
			$this->getEngine()
		);
	}

	protected function tearDown(): void {
		unset( $this->scribuntoLuaLibrary );
		parent::tearDown();;
	}

	/**
	 * Accesses an instance of class {@see ScribuntoLuaLibrary}
	 *
	 * @return ScribuntoLuaLibrary ScribuntoLuaLibrary
	 */
	public function getScribuntoLuaLibrary() {
		return $this->scribuntoLuaLibrary;
	}

	/**
	 * Only needed for MW 1.31
	 */
	public function run( TestResult $result = null ): TestResult {
		// MW 1.31
		$this->setCliArg( 'use-normal-tables', true );

		return parent::run( $result );
	}

	/**
	 * @see Scribunto_LuaEngineTestBase -> MediaWikiTestCase
	 */
	protected function overrideMwServices( \Config $configOverrides = null, array $services = [] ) {

		/**
		 * `MediaWikiTestCase` isolates the result with  `MediaWikiTestResult` which
		 * ecapsultes the commandline args and since we need to use "real" tables
		 * as part of "use-normal-tables" we otherwise end-up with the `CloneDatabase`
		 * to create TEMPORARY  TABLE by default as in:
		 *
		 * CREATE TEMPORARY  TABLE `unittest_smw_di_blob` (LIKE `smw_di_blob`) and
		 * because of the TEMPORARY TABLE, MySQL (not MariaDB) will complain
		 * about things like:
		 *
		 * SELECT p.smw_title AS prop, o_id AS id0, o0.smw_title AS v0, o0.smw_namespace
		 * AS v1, o0.smw_iw AS v2, o0.smw_sortkey AS v3, o0.smw_subobject AS v4,
		 * o0.smw_sort AS v5 FROM `unittest_smw_di_wikipage` INNER JOIN
		 * `unittest_smw_object_ids` AS p ON p_id=p.smw_id INNER JOIN
		 * `unittest_smw_object_ids` AS o0 ON o_id=o0.smw_id WHERE (s_id='29') AND
		 * (p.smw_iw!=':smw') AND (p.smw_iw!=':smw-delete')
		 *
		 * Function: SMW\SQLStore\EntityStore\SemanticDataLookup::fetchSemanticDataFromTable
		 * Error: 1137 Can't reopen table: 'p' ()
		 *
		 * The reason is that `unittest_smw_object_ids` was created as TEMPORARY TABLE
		 * and p is referencing to a TEMPORARY TABLE as well which isn't allowed in
		 * MySQL.
		 *
		 * "You cannot refer to a TEMPORARY table more than once in the same query" [0]
		 *
		 * [0] https://dev.mysql.com/doc/refman/8.0/en/temporary-table-problems.html
		 */

		// MW 1.32+
		$this->setCliArg( 'use-normal-tables', true );

		parent::overrideMwServices( $configOverrides, $services );
	}

}
