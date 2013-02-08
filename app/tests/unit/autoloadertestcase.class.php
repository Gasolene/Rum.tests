<?php
    namespace MyApp\Models;
	include_once __ROOT__ . '/app/includes/string.inc';

	class AutoLoaderTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testAutoLoader() {
			//$this->assertTrue( defined( '__MY_TEST__' ));
			//$this->assertTrue( __MY_TEST__ === 'foo' );
		}
	}
?>