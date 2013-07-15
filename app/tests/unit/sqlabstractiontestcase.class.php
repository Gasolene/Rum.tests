<?php
    namespace MyApp\Models;
    use System\Data\QueryBuilder;
    use \System\DB\DataAdapter;
    use \System\DB\DataSet;
    use \MyApp\App;

	class SQLAbstractionTestCase extends \System\Testcase\UnitTestCaseBase {

		/**
		 * mssql adapter
		 * @var MSSQLDataAdapter
		 */
		protected $MSSQL;

		function prepare() {

			if(!$this->MSSQL) {
				$conStr = \Rum::app()->config->appsettings["mssql_conn_str"];
				$this->MSSQL = DataAdapter::create($conStr);
			}

			// load fixture(s)
			$this->MSSQL->execute(file_get_contents(__ROOT__.'/app/tests/fixtures/mssql_school.sql'));

			if( \System\AppServlet::getInstance()->dataAdapter instanceof \System\Data\MSSqlDataAdapter ) :
				$this->loadFixtures( 'mssql_school.sql' );
			else :
				$this->loadFixtures( 'mysql_school.sql' );
			endif;
		}

		function cleanup() {
		}

		function testSelect() {
			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->select();
			$query->select( 'mytable' );
			$query->select( 'mytable2', 'mycolumn1' );
			$query->select( 'mytable2', 'mycolumn2', 'mycolalias' );

			$query->from( 'table1' );
			$query->from( 'table2', 'mytablealias' );

			$query->leftJoin( 'jointable', 'joincol', 'mytable', 'mycol' );

			$query->where( 'table1', 'column1', '=', '1' );
			$query->orderby( 'table1', 'column1', 'desc' );
			$query->having( 'column1', '=', '1' );
			$query->groupby( 'table1', 'column1' );
		}

		function testUpdate() {
			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->update( 'mytable' );
			$query->set( 'table1', 'column1', 'test' );
			$query->set( 'table2', 'column2', 'test2' );
		}

		function testInsert() {
			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->insertInto( 'mytable', array( 'col1','col2','col3' ));
			$query->values( array( 'val1','val2','val3' ));
		}

		function testDelete() {
			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->delete();
			$query->from( 'table2' );
		}

		function testExceptions() {
			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->update( 'mytable' );

			$this->expectException();
			$query->from( 'table2' );

			$this->expectException();
			$query->select( '*' );

			$this->expectException();
			$query->groupBy( 'table', 'col' );
		}

		function testMSSQLDriver() {
			$query = $this->runDriverTest($this->MSSQL);
		}

		function testMySQLiDriver() {
			$query = $this->runDriverTest(\System\Base\ApplicationBase::getInstance()->dataAdapter);
		}

		function runDriverTest($da) {
			$query = $da->queryBuilder();

			$query->insertInto( 'School', array( 'School_name' ));
			$query->values( array( 'Northwind' ));

			$da->execute( $query->getQuery() );

			$query = $da->queryBuilder();

			$query->insertInto( 'classrooms', array( 'name', 'School_id' ));
			$query->values( array( 'Science', '1' ));

			$da->execute( $query->getQuery() );

			$query = $da->queryBuilder();

			$query->insertInto( 'student', array( 'student_name', 'student_age' ));
			$query->values( array( 'Bobby', '16' ));

			$da->execute( $query->getQuery() );

			$query = $da->queryBuilder();

			$query->insertInto( 'student_classrooms', array( 'student_id', 'classroom_id' ));
			$query->values( array( '1', '1' ));

			$da->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( $da, 'School' )->rows[0]['School_name'], 'Northwind' );
			$this->assertEqual( $this->getRows( $da, 'classrooms' )->rows[0]['name'], 'Science' );
			$this->assertEqual( $this->getRows( $da, 'student' )->rows[0]['student_name'], 'Bobby' );
			$this->assertEqual( $this->getRows( $da, 'student_classrooms' )->rows[0]['student_id'], '1' );
			$this->assertEqual( $this->getRows( $da, 'student_classrooms' )->rows[0]['classroom_id'], '1' );

			$query = $da->queryBuilder();

			$query->select( 'student', 'student_name' );
			$query->select( 'classrooms' );
			$query->select( 'School', 'School_name' );
			$query->from( 'student' );
			$query->innerJoin( 'student_classrooms', 'student_id', 'student', 'student_id' );
			$query->leftJoin( 'classrooms', 'classroom_id', 'student_classrooms', 'classroom_id' );
			$query->innerJoin( 'School', 'School_id', 'classrooms', 'School_id' );

			$row = $da->openDataSet( $query->getQuery() )->row;
			$this->assertEqual( $row['School_name'], 'Northwind' );
			$this->assertEqual( $row['name'], 'Science' );
			$this->assertEqual( $row['student_name'], 'Bobby' );
			$this->assertFalse( isset( $row['student_id'] ));

			$query = $da->queryBuilder();

			$query->update( 'student' );
			$query->set( 'student', 'student_name', 'Sally' );
			$query->where( 'student', 'student_name', '=', 'Alan' );

			$da->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( $da, 'student' )->rows[0]['student_name'], 'Bobby' );

			$query = $da->queryBuilder();

			$query->update( 'student' );
			$query->set( 'student', 'student_name', 'Sally' );
			$query->where( 'student', 'student_name', '=', 'Bobby' );

			$da->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( $da, 'student' )->rows[0]['student_name'], 'Sally' );

			$query = $da->queryBuilder();

			$query->delete();
			$query->from( 'student' );
			$query->where( 'student', 'student_name', '=', 'Bobby' );

			$da->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( $da, 'student' )->count, 1 );

			$query = $da->queryBuilder();

			$query->delete();
			$query->from( 'student' );
			$query->where( 'student', 'student_name', '=', 'Sally' );

			$da->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( $da, 'student' )->count, 0 );
		}

		function getRows( $da, $table ) {
			return $da->queryBuilder()->select()->from($table)->openDataSet();
		}
	}
?>