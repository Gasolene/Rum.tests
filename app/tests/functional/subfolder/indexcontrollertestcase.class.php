<?php
    namespace MyApp\Controllers\Subfolder;

	class IndexControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testGet() {
			$this->get();

			$this->assertResponse( 'hello world' );
		}
	}
?>