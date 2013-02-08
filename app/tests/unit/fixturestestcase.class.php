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

		function testXMLFixture() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `category`;');
            $this->assertEqual($ds->count, 2);
            $this->assertEqual($ds->rows[0]["category_id"], 1);
            $this->assertEqual($ds->rows[1]["category_id"], 3);

            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `customer`;');
            $this->assertEqual($ds->count, 0);
        }

		function testXMLFixture2() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `category`;');
            $this->assertEqual($ds->count, 2);
            $this->assertEqual($ds->rows[0]["category_id"], 1);
            $this->assertEqual($ds->rows[1]["category_id"], 3);

            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `customer`;');
            $this->assertEqual($ds->count, 0);
        }

		function testSQLFixture() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `group`;');
            $this->assertEqual($ds->count, 2);
            $this->assertEqual($ds->rows[0]["group_id"], 2);
            $this->assertEqual($ds->rows[1]["group_id"], 4);

            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `customer`;');
            $this->assertEqual($ds->count, 0);
        }

		function testSQLFixture2() {
            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `group`;');
            $this->assertEqual($ds->count, 2);
            $this->assertEqual($ds->rows[0]["group_id"], 2);
            $this->assertEqual($ds->rows[1]["group_id"], 4);

            $ds = \System\AppServlet::getInstance()->dataAdapter->openDataSet('select * from `customer`;');
            $this->assertEqual($ds->count, 0);
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