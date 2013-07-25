<?php
    namespace MyApp\Models;
    use \System\DB\DataAdapter;
    use \System\DB\DataSet;

	class DataAdapterTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
			\System\Base\Build::clean();
			copy( \Rum::config()->fixtures . '/text.csv', __TMP_PATH__ . '/text.csv' );
		}

		function cleanup() {
			unlink( __TMP_PATH__ . '/text.csv' );
		}

		function testMSSql_DataAdapter() {
			$conStr = \Rum::app()->config->appsettings["mssql_conn_str"];

			$this->expectError();
			$da = DataAdapter::create($conStr);

			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_test.sql'));
			$this->SqlDataAdapterTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_test.sql'));
			$this->DeleteEmptyTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_test.sql'));
			//$this->DBCachingTest($da);
		}

		function testMySql_DataAdapter() {
			$conStr = \Rum::app()->config->appsettings["mysql_conn_str"];

			$this->expectError();
			$da = DataAdapter::create($conStr);

			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->SqlDataAdapterTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->DBCachingTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->DeleteEmptyTest($da);
		}

		function testMySqli_DataAdapter() {
			$conStr = \Rum::app()->config->appsettings["mysqli_conn_str"];

			$da = DataAdapter::create($conStr);

			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->SqlDataAdapterTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->DBCachingTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->DeleteEmptyTest($da);
		}

		function xtestPDOMSSQL_DataAdapter() {
			$conStr = \Rum::app()->config->appsettings["pdo_mssql_conn_str"];

			$this->expectError();
			$da = DataAdapter::create($conStr, \Rum::app()->config->appsettings["pdo_mssql_username"], \Rum::app()->config->appsettings["pdo_mssql_password"]);

			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_test.sql'));
			$this->SqlDataAdapterTest($da);
			//$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_test.sql'));
			//$this->DBCachingTest($da);
			//$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_test.sql'));
			//$this->DeleteEmptyTest($da);
		}

		function testPDOMySQL_DataAdapter() {
			$conStr = \Rum::app()->config->appsettings["pdo_mysql_conn_str"];

			$this->expectError();
			$da = DataAdapter::create($conStr, \Rum::app()->config->appsettings["pdo_mysql_username"], \Rum::app()->config->appsettings["pdo_mysql_password"]);

			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->SqlDataAdapterTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			//$this->DBCachingTest($da);
			$da->executeBatch(file_get_contents(__ROOT__.'/app/tests/fixtures/mysql_test.sql'));
			$this->DeleteEmptyTest($da);
		}

		function testDir_DataAdapter() {

			// create connection
			$conn = DataAdapter::create( 'driver=dir;source=' . __SYSTEM_PATH__ . '/comm' );
			$ds = $conn->openDataSet();

			// fields
			$this->assertTrue( $ds->fieldMeta[0]->name    === 'name' );
			$this->assertTrue( $ds->fieldMeta[1]->name    === 'path' );
			$this->assertTrue( $ds->fieldMeta[2]->name    === 'size' );
			$this->assertTrue( $ds->fieldMeta[3]->name    === 'modified' );
			$this->assertTrue( $ds->fieldMeta[4]->name    === 'accessed' );
			$this->assertTrue( $ds->fieldMeta[5]->name    === 'created' );
			$this->assertTrue( $ds->fieldMeta[6]->name    === 'isfolder' );
			$this->assertEqual( count($ds->rows), 3 );

			$this->assertTrue( $ds->seek( 'name', 'mail' ));
			$this->assertTrue( $ds['isfolder'] === 1 );

			$this->assertTrue( $ds->seek( 'name', 'httpwebrequest.class.php' ));
			$this->assertTrue( $ds['isfolder'] === 0 );

			$this->assertTrue( $conn->close() );
		}

		function testText_DataAdapter() {

			// create connection
			$conn = DataAdapter::Create( 'driver=text;format=TabDelimited;source=' . __TMP_PATH__ . '/text.csv' );
			$ds = $conn->openDataSet();

			// fields
			$this->assertEqual( $ds->fieldMeta[0]->name, 'string' );
			$this->assertEqual( $ds->fieldMeta[0]->type, 'string' );
			$this->assertEqual( count($ds->fieldMeta), 3 );

			// open
			$this->assertEqual( $ds->rows[0]['string']    , 'George' );
			$this->assertTrue( $ds->rows[0]['string']     != 'george' );
			$this->assertEqual( $ds->rows[1]['string']    , 'bob' );
			$this->assertEqual( $ds->rows[1]['float']     , 57 );

			//$this->assertEqual( $ds->getSum( 'float' ), 99 );
			//$this->assertEqual( $ds->getMax( 'float' ), 57 );
			//$this->assertEqual( $ds->getMin( 'float' ), 16.4 );
			//$this->assertEqual( $ds->getAvg( 'float' ), 33 );
			$this->assertEqual( $ds->count            , 3 );

			$this->assertTrue(  isset( $ds->rows[2]['date'] ));
			$this->assertTrue( !isset( $ds->rows[3] ));

			// sort
			$ds->sort( 'float' );
			$this->assertEqual( $ds->rows[0]['float'], 16.4 );
			$this->assertEqual( $ds->rows[2]['float'], 57 );
			$ds->sort( 'float', TRUE );
			$this->assertEqual( $ds->rows[2]['float'], 16.4 );
			$this->assertEqual( $ds->rows[0]['float'], 57 );
			$ds->sort( 'date', TRUE );
			$this->assertEqual( $ds->rows[0]['date'], '2008-06-10' );
			$this->assertEqual( $ds->rows[2]['date'], '2002-02-03' );

			// nav
			$ds->first();
			$this->assertEqual( $ds['string']    , 'sally' );
			$ds->next();
			$this->assertEqual( $ds['string']   , 'George' );
			$ds->next();
			$this->assertEqual( $ds['string']    , 'bob' );
			$this->assertFalse( $ds->eof() );
			$ds->next();
			$this->assertTrue( $ds->eof() );
			$ds->last();
			$this->assertEqual( $ds['string']    , 'bob' );
			$ds->prev();
			$this->assertEqual( $ds['string']    , 'George' );
			$ds->prev();
			$this->assertEqual( $ds['string']    , 'sally' );
			$this->assertFalse( $ds->bof() );
			$ds->prev();
			$this->assertTrue( $ds->bof() );
			$ds->first();
			$this->assertEqual( $ds['string']    , 'sally' );
			$ds->move( 2 );
			$this->assertEqual( $ds['string']    , 'bob' );

			// find
			$ds->seek( 'float', 16.4 );
			$this->assertEqual( $ds['string']    , 'George' );
			$ds->seek( 'string', 'bOB', 0 );
			$this->assertEqual( $ds['string']    , 'George' );
			$ds->seek( 'string', 'bOB', 1 );
			$this->assertEqual( $ds['string']    , 'bob' );

			// page
			$ds->pageSize = 1;
			$ds->page( 3 );

			$this->assertTrue( $ds['string']    === 'bob' );
			$this->assertTrue( $ds->page                == 3 );
			$this->assertTrue( $ds->pageCount           == 3 );
			$ds->pageSize = 2;
			$this->assertTrue( $ds->pageCount()         == 2 );
			$ds->pageSize = 4;
			$this->assertTrue( $ds->pageCount()         == 1 );

			// filter
			$ds->filter( 'float', '>=', 25.6 );
			$this->assertTrue( $ds->rows[0]['string']    === 'sally' );
			$this->assertTrue( $ds->rows[1]['string']    === 'bob' );

			$ds->filter( 'date', '<', '2008-06-10' );
			$this->assertTrue( $ds->rows[0]['string']    === 'bob' );

			// insert
			$ds['string'] = 'Ralph';
			$ds['date']   = '2003-05-05';
			$ds['float']  = 2.101;

			$ds->insert();
			$ds2 = $conn->openDataSet();
			$this->assertEqual( $ds->rows[1]['string']   , 'Ralph' );
			$this->assertEqual( $ds->rows[1]['date']     , '2003-05-05' );
			$this->assertEqual( $ds->rows[1]['float']    , 2.101 );

			$this->assertEqual( $ds2->rows[1]['string']  , 'Ralph' );
			$this->assertEqual( $ds2->rows[1]['date']    , '2003-05-05' );
			$this->assertEqual( $ds2->rows[1]['float']   , 2.101 );

			// getstring
			$this->assertTrue( $ds->getCSVString() === "\"string\"\t\"float\"\t\"date\"\n\"bob\"\t\"57\"\t\"2002-02-03\"\n\"Ralph\"\t\"2.101\"\t\"2003-05-05\"" );
			$this->assertTrue( $ds->getCSVString("\"",",","\t","NULL",true,1) === "\"string\",\"float\",\"date\"\t\"bob\",\"57\",\"2002-02-03\"" );

			$ds['string'] = 'alex';
			$ds['float'] = '36.6';
			$ds['date'] = '2000-01-01';
			$ds->insert();

			$ds->delete();
			$ds2 = $conn->openDataSet();
			$this->assertTrue( $ds->count                    === 2 );
			$this->assertTrue( $ds->cursor                   === 2 );
			$this->assertTrue( $ds->eof() );
			$this->assertTrue( $ds2->count                   === 2 );
			$this->assertTrue( $ds2->cursor                  === 0 );
			$ds->first();

			$ds->delete();
			$this->assertTrue( $ds->count                    === 1 );
			$this->assertTrue( $ds->cursor                   === 0 );
			$this->assertTrue( $ds['string']            === 'Ralph' );
			$this->assertTrue( $ds['float']             === 2.101 );
			$this->assertTrue( $ds['date']              === '2003-05-05' );
			$this->assertFalse( $ds->eof() );

			$ds->first();
			$ds['string'] = 'james';
			$ds['float'] = 55.8;
			$ds['date'] = '2000-01-01';
			$ds->update();
			$ds2 = $conn->openDataSet();
			$this->assertTrue( $ds['string']            == 'james' );
			$this->assertTrue( $ds['float']             == 55.8 );
			$this->assertTrue( $ds['date']              == '2000-01-01' );
			$this->assertTrue( $ds2['string']            == 'james' );
			$this->assertTrue( $ds2['float']             == 55.8 );
			$this->assertTrue( $ds2['date']              == '2000-01-01' );

			$this->assertTrue( $conn->close() );
		}

		private function SqlDataAdapterTest(\System\DB\DataAdapter &$da ) {
strt();
			$ds = $da->queryBuilder()->select('*')->from('test')->openDataSet();

			$this->assertEqual( $ds->fields[0]                , 'test_id' );
			$this->assertEqual( $ds->fieldMeta[0]->name       , 'test_id' );
			$this->assertEqual( $ds->fieldMeta[0]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[0]->length     , 10.0 );
			$this->assertEqual( $ds->fieldMeta[0]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->integer    , TRUE );
			$this->assertEqual( $ds->fieldMeta[0]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->numeric    , TRUE );
			$this->assertEqual( $ds->fieldMeta[0]->notNull    , TRUE );
			$this->assertEqual( $ds->fieldMeta[0]->primaryKey , TRUE );
			$this->assertEqual( $ds->fieldMeta[0]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[0]->blob       , FALSE );
			if($da instanceof \System\DB\PDO\PDODataAdapter) {} else {
				$this->assertEqual( $ds->fieldMeta[0]->autoIncrement , TRUE );
			}
			$this->assertEqual( $ds->fields[1]                , 'test_double' );
			$this->assertEqual( $ds->fieldMeta[1]->name       , 'test_double' );
			$this->assertEqual( $ds->fieldMeta[1]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[1]->length     , 5.0 );
			$this->assertEqual( $ds->fieldMeta[1]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->real       , TRUE );
			$this->assertEqual( $ds->fieldMeta[1]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->numeric    , TRUE );
			$this->assertEqual( $ds->fieldMeta[1]->notNull    , TRUE );
			$this->assertEqual( $ds->fieldMeta[1]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->blob       , FALSE );
			$this->assertEqual( $ds->fieldMeta[1]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[2]                , 'test_float' );
			$this->assertEqual( $ds->fieldMeta[2]->name       , 'test_float' );
			$this->assertEqual( $ds->fieldMeta[2]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[2]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->real       , TRUE );
			$this->assertEqual( $ds->fieldMeta[2]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->numeric    , TRUE );
			$this->assertEqual( $ds->fieldMeta[2]->notNull    , TRUE );
			$this->assertEqual( $ds->fieldMeta[2]->primaryKey , FALSE );
			if($ds->dataAdapter instanceof \System\DB\MSSQL\MSSQLDataAdapter || $ds->dataAdapter instanceof \System\DB\PDO\PDODataAdapter) {} else {
				$this->assertEqual( $ds->fieldMeta[2]->unique     , TRUE );
			}
			$this->assertEqual( $ds->fieldMeta[2]->blob     , FALSE );
			$this->assertEqual( $ds->fieldMeta[2]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[3]                , 'test_decimal' );
			$this->assertEqual( $ds->fieldMeta[3]->name       , 'test_decimal' );
			$this->assertEqual( $ds->fieldMeta[3]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[3]->length     , 8.0 );
			$this->assertEqual( $ds->fieldMeta[3]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->real       , TRUE );
			$this->assertEqual( $ds->fieldMeta[3]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->numeric    , TRUE );
			$this->assertEqual( $ds->fieldMeta[3]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->blob       , FALSE );
			$this->assertEqual( $ds->fieldMeta[3]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[4]                , 'test_bool' );
			$this->assertEqual( $ds->fieldMeta[4]->name       , 'test_bool' );
			$this->assertEqual( $ds->fieldMeta[4]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[4]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->integer    , TRUE );
			$this->assertEqual( $ds->fieldMeta[4]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->boolean    , TRUE );
			$this->assertEqual( $ds->fieldMeta[4]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->numeric    , TRUE );
			$this->assertEqual( $ds->fieldMeta[4]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->blob       , FALSE );
			$this->assertEqual( $ds->fieldMeta[4]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[5]                , 'test_char' );
			$this->assertEqual( $ds->fieldMeta[5]->name       , 'test_char' );
			$this->assertEqual( $ds->fieldMeta[5]->table      , 'test' );
			if($ds->dataAdapter instanceof \System\DB\MySQL\MySQLDataAdapter ||
				$ds->dataAdapter instanceof \System\DB\MySQLi\MySQLiDataAdapter) {} else {
				$this->assertEqual( $ds->fieldMeta[5]->length     , 2.0 ); // Bug in MySQL client 5.0
			}
			$this->assertEqual( $ds->fieldMeta[5]->string     , TRUE );
			$this->assertEqual( $ds->fieldMeta[5]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->numeric    , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->blob       , FALSE );
			$this->assertEqual( $ds->fieldMeta[5]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[6]                , 'test_varchar' );
			$this->assertEqual( $ds->fieldMeta[6]->name       , 'test_varchar' );
			$this->assertEqual( $ds->fieldMeta[6]->table      , 'test' );
			//$this->assertEqual( $ds->fieldMeta[6]->length     , 80.0 );
			$this->assertEqual( $ds->fieldMeta[6]->string     , TRUE );
			$this->assertEqual( $ds->fieldMeta[6]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->numeric    , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->blob       , FALSE );
			$this->assertEqual( $ds->fieldMeta[6]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[7]                , 'test_blob' );
			$this->assertEqual( $ds->fieldMeta[7]->name       , 'test_blob' );
			$this->assertEqual( $ds->fieldMeta[7]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[7]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[7]->blob       , TRUE );
			$this->assertEqual( $ds->fieldMeta[7]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[8]                , 'test_varbinary' );
			$this->assertEqual( $ds->fieldMeta[8]->name       , 'test_varbinary' );
			$this->assertEqual( $ds->fieldMeta[8]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[8]->string     , TRUE );
			$this->assertEqual( $ds->fieldMeta[8]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->numeric    , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[8]->unique     , FALSE );
			if($ds->dataAdapter instanceof \System\DB\PDO\PDODataAdapter) {
				$this->assertEqual( $ds->fieldMeta[8]->blob       , FALSE );
			}
			else {
				$this->assertEqual( $ds->fieldMeta[8]->blob       , TRUE );
			}
			$this->assertEqual( $ds->fieldMeta[8]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[9]                , 'test_date' );
			$this->assertEqual( $ds->fieldMeta[9]->name       , 'test_date' );
			$this->assertEqual( $ds->fieldMeta[9]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[9]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->numeric    , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[9]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[10]                , 'test_time' );
			$this->assertEqual( $ds->fieldMeta[10]->name       , 'test_time' );
			$this->assertEqual( $ds->fieldMeta[10]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[10]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->time       , TRUE );
			$this->assertEqual( $ds->fieldMeta[10]->datetime   , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->numeric    , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[10]->autoIncrement , FALSE );

			$this->assertEqual( $ds->fields[11]                , 'test_datetime' );
			$this->assertEqual( $ds->fieldMeta[11]->name       , 'test_datetime' );
			$this->assertEqual( $ds->fieldMeta[11]->table      , 'test' );
			$this->assertEqual( $ds->fieldMeta[11]->string     , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->integer    , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->real       , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->boolean    , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->date       , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->time       , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->datetime   , TRUE );
			$this->assertEqual( $ds->fieldMeta[11]->numeric    , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->notNull    , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->primaryKey , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->unique     , FALSE );
			$this->assertEqual( $ds->fieldMeta[11]->autoIncrement , FALSE );

			// destructive manipulations

			// test insert by __get()
			$ds['test_double'] = 4.3;
			$ds['test_float'] = 0.555;
			$this->assertNull( $ds->insert() );
			$rows = array_values($ds->rows);
			$this->assertEqual( $ds->row['test_id'] , 1 );
			$this->assertEqual( $ds->rows[0]['test_id'] , 1 );
			$this->assertEqual( $ds->rows[0]['test_double'] , 4.300 );
			$this->assertEqual( $ds->row['test_double'] , 4.300 );
			$this->assertEqual( $ds->rows[0]['test_double'] , 4.300 );

			$ds2 = $ds->dataAdapter->openDataSet( 'select * from test' );
			$rows2 = array_values($ds2->rows);
			$this->assertEqual( $ds2->rows[0]['test_double'] , 4.300 );
			$this->assertEqual( $ds2->row['test_double'] , 4.300 );
			$this->assertEqual( $ds2->rows[0]['test_double'] , 4.300 );

			// test update by __get()
			$ds['test_double'] = 4.4;
			$this->assertNull( $ds->update() );
			$rows = array_values($ds->rows);
			$this->assertEqual( $ds->rows[0]['test_double'] , 4.400 );
			$this->assertEqual( $ds->row['test_double'] , 4.400 );
			$this->assertEqual( $ds->rows[0]['test_double'] , 4.400 );

			$ds2 = $ds->dataAdapter->openDataSet( 'select * from test' );
			$rows2 = $ds2->rows;

			$this->assertEqual( $rows2[0]['test_double'] , 4.400 );
			$this->assertEqual( $ds2->rows[0]['test_double'] , 4.400 );
			$this->assertEqual( $ds2->row['test_double'] , 4.400 );
			$this->assertEqual( $ds2->rows[0]['test_double'] , 4.400 );

			// test insert by row[]

			$ds['test_id'] = null;
			$ds['test_double'] = 6.1;
			$ds['test_float'] = 0.333;
			$this->assertNull( $ds->insert() );
			$rows = $ds->rows;
			$this->assertEqual( $ds->row['test_id'] , 2 );
			$this->assertEqual( $ds->rows[1]['test_id'] , 2 );
			$this->assertEqual( $ds->rows[1]['test_double'] , 6.100 );
			$this->assertEqual( $ds->row['test_double'] , 6.100 );
			$this->assertEqual( $ds->rows[1]['test_double'] , 6.100 );
			$this->assertEqual( $rows[1]['test_double'] , 6.100 );

			$ds2 = $ds->dataAdapter->openDataSet( 'select * from test' );
			$ds2->last();
			$rows2 = array_values($ds2->rows);
			$this->assertEqual( $rows2[1]['test_double'] , 6.100 );
			$this->assertEqual( $ds2->rows[1]['test_double'] , 6.100 );
			$this->assertEqual( $ds2->row['test_double'] , 6.100 );
			$this->assertEqual( $ds2->rows[1]['test_double'] , 6.100 );
			$this->assertEqual( $ds2->rows[1]['test_double'] , 6.100 );

			// test update by row[]
			$ds['test_double'] = 5.8;
			$this->assertNull( $ds->update() );
			$rows = $ds->rows;
			$this->assertEqual( $ds->rows[1]['test_double'] , 5.800 );
			$this->assertEqual( $ds->row['test_double'] , 5.800 );

			$ds2 = $ds->dataAdapter->openDataSet( 'select * from test' );
			$ds2->last();
			$rows2 = array_values($ds2->rows);
			$this->assertEqual( $ds2->rows[1]['test_double'] , 5.800 );
			$this->assertEqual( $ds2->row['test_double'] , 5.800 );
			$this->assertEqual( $ds2->rows[1]['test_double'] , 5.800 );

			// test pointers
			$ds['test_id'] = null;
			$ds['test_double'] = 6.4;
			$ds['test_float'] = 0.111;
			$this->assertNull( $ds->insert() );
			$this->assertEqual( $ds->count, 3 );

			$this->assertNull( $ds->first() );
			$this->assertFalse( $ds->bof() );
			$this->assertFalse( $ds->eof() );
			$this->assertEqual( $ds->cursor, 0 );
			$this->assertEqual( $ds['test_id'], 1 );
			$this->assertEqual( $ds['test_double'], 4.4 );

			$this->assertNull( $ds->next() );
			$this->assertFalse( $ds->bof(), 0 );
			$this->assertFalse( $ds->eof(), 0 );
			$this->assertEqual( $ds->cursor, 1 );
			$this->assertEqual( $ds['test_id'], 2 );
			$this->assertEqual( $ds['test_double'], 5.8 );

			$this->assertNull( $ds->next() );
			$this->assertFalse( $ds->bof() );
			$this->assertFalse( $ds->eof() );
			$this->assertEqual( $ds->cursor, 2 );
			$this->assertEqual( $ds['test_id'], 3 );
			$this->assertEqual( $ds['test_double'], 6.4 );

			$this->assertNull( $ds->next() );
			$this->assertFalse( $ds->bof() );
			$this->assertTrue( $ds->eof() );
			$this->assertEqual( $ds->cursor, 3 );
			$this->assertEqual( $ds['test_id'], null );
			$this->assertEqual( $ds['test_double'], null );

			$ds->last();
			$this->assertEqual( $ds->cursor, 2 );
			$this->assertEqual( $ds['test_id'], 3 );
			$this->assertEqual( $ds['test_double'], 6.4 );

			$ds->first();
			$this->assertEqual( $ds['test_id'], 1 );
			$this->assertEqual( $ds['test_double'], 4.4 );

			$this->assertNull( $ds->prev() );
			$this->assertTrue( $ds->bof() );
			$this->assertFalse( $ds->eof() );
			$this->assertEqual( $ds->cursor, -1 );
			$this->assertEqual( $ds['test_id'], null );
			$this->assertEqual( $ds['test_double'], null );

			// test delete
			$this->assertNull( $ds->first() );
			$this->assertNull( $ds->next() );
			$this->assertNull( $ds->delete() );
			$this->assertEqual( $ds->count, 2 );
			$this->assertEqual( $ds->cursor, 1 );
			$this->assertEqual( $ds['test_id'], 3 );
			$this->assertEqual( $ds['test_double'], 6.4 );

			$ds2 = $ds->dataAdapter->openDataSet( 'select * from test' );
			$this->assertNull( $ds2->last() );
			$this->assertEqual( $ds2->count, 2 );
			$this->assertEqual( $ds2->cursor, 1 );
			$this->assertEqual( $ds2['test_id'], 3 );
			$this->assertEqual( $ds2['test_double'], 6.4 );

			$ds->first();
			$ds["test_date"] = '2002-01-01';
			$ds->update();
			$ds->next();
			$ds["test_date"] = '2000-01-01';
			$ds->update();

			// test agg functions
			$this->assertEqual( $ds->getSum( 'test_double' ), 10.8 );
			$this->assertEqual( $ds->getMax( 'test_double' ), 6.4 );
			$this->assertEqual( $ds->getMin( 'test_double' ), 4.4 );
			$this->assertEqual( $ds->getAvg( 'test_double' ), 5.4 );
stp();
		}

		private function DBCachingTest($da) {
			\Rum::app()->cache->flush();

			$ds_nocache = $da->openDataSet( 'select * from test' );
			$da->disableCaching();
			$ds = $da->openDataSet( 'select * from test' );
			$this->assertEqual($ds->count, 0);

			$ds_nocache["test_double"] = 4.4;
			$ds_nocache["test_float"] = .555;
			$ds_nocache->insert();

			$ds = $da->openDataSet( 'select * from test' );
			$this->assertEqual($ds->count, 1);
			$da->enableCaching(3);
			$this->assertEqual($ds->count, 1);
			$ds = $da->openDataSet( 'select * from test' );
			$this->assertEqual($ds->count, 1);

			$ds_nocache["test_id"] = null;
			$ds_nocache["test_double"] = 6.6;
			$ds_nocache["test_float"] = .777;
			$ds_nocache->insert();

			$ds = $da->openDataSet( 'select * from test' );
			$this->assertEqual($ds->count, 1);

			$da->disableCaching();
			$ds = $da->openDataSet( 'select * from test' );
			$this->assertEqual($ds->count, 2);
		}

		private function DeleteEmptyTest(\System\DB\DataAdapter &$da) {
			$ds = $da->openDataSet('select * from test');

			$this->expectException();
			$ds->delete();
		}
	}
?>