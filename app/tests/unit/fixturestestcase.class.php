<?php
    namespace MyApp\Models;
	class FixturesTestCase extends \System\Testcase\UnitTestCaseBase {

        protected $fixtures = 'test-fixture.sql, test-fixture.xml, test.csv';

		function prepare() {
		}

		function cleanup() {
		}

		function testLoad() {
            
        }

		function testSQLXMLFixture() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `fruit`;');
            $this->assertEqual($ds->count, 3);
            $this->assertEqual($ds->rows[0]["fruit_id"], 2);
            $this->assertEqual($ds->rows[1]["fruit_id"], 3);
            $this->assertEqual($ds->rows[2]["fruit_id"], 4);

            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `region`;');
            $this->assertEqual($ds->count, 3);
        }

		function testSQLXMLFixture2() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `fruit`;');
            $this->assertEqual($ds->count, 3);
            $this->assertEqual($ds->rows[0]["fruit_id"], 2);
            $this->assertEqual($ds->rows[1]["fruit_id"], 3);
            $this->assertEqual($ds->rows[2]["fruit_id"], 4);

            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `region`;');
            $this->assertEqual($ds->count, 3);
        }

		function testCSVFixture() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `test`;');
            $this->assertEqual($ds->count, 3);
            $this->assertEqual($ds->rows[0]["test_id"], 1);
            $this->assertEqual($ds->rows[1]["test_id"], 2);
			$this->assertEqual($ds->rows[2]["test_id"], 3);
			$this->assertEqual($ds->rows[0]["test_float"], 0.16);
            $this->assertEqual($ds->rows[1]["test_float"], 0.57);
			$this->assertEqual($ds->rows[2]["test_float"], 0.025);
			$this->assertEqual($ds->rows[0]["test_varchar"], "George");
            $this->assertEqual($ds->rows[1]["test_varchar"], "bob");
			$this->assertEqual($ds->rows[2]["test_varchar"], "sally");
        }
	}
?>