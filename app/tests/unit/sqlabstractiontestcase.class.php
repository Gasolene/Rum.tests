<?php
    namespace MyApp\Models;
    use System\Data\QueryBuilder;

	class SQLAbstractionTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
			if( \System\AppServlet::getInstance()->dataAdapter instanceof \System\Data\MSSqlDataAdapter ) :
				$this->loadFixtures( 'mssql_school.sql' );
			else :
				$this->loadFixtures( 'mysql_school.sql' );
			endif;
		}

		function cleanup() {
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE classrooms;' );
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE School;' );
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE student;' );
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE student_classrooms;' );
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE page;' );
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE team;' );
			\System\AppServlet::getInstance()->dataAdapter->execute( 'DROP TABLE player;' );
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

		function testDriver() {
			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->insertInto( 'School', array( 'School_name' ));
			$query->values( array( 'Northwind' ));

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->insertInto( 'classrooms', array( 'name', 'School_id' ));
			$query->values( array( 'Science', '1' ));

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->insertInto( 'student', array( 'student_name', 'student_age' ));
			$query->values( array( 'Bobby', '16' ));

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->insertInto( 'student_classrooms', array( 'student_id', 'classroom_id' ));
			$query->values( array( '1', '1' ));

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( 'School' )->rows[0]['School_name'], 'Northwind' );
			$this->assertEqual( $this->getRows( 'classrooms' )->rows[0]['name'], 'Science' );
			$this->assertEqual( $this->getRows( 'student' )->rows[0]['student_name'], 'Bobby' );
			$this->assertEqual( $this->getRows( 'student_classrooms' )->rows[0]['student_id'], '1' );
			$this->assertEqual( $this->getRows( 'student_classrooms' )->rows[0]['classroom_id'], '1' );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->select( 'student', 'student_name' );
			$query->select( 'classrooms' );
			$query->select( 'School', 'School_name' );
			$query->from( 'student' );
			$query->innerJoin( 'student_classrooms', 'student_id', 'student', 'student_id' );
			$query->leftJoin( 'classrooms', 'classroom_id', 'student_classrooms', 'classroom_id' );
			$query->innerJoin( 'School', 'School_id', 'classrooms', 'School_id' );

			$row = \System\AppServlet::getInstance()->dataAdapter->openDataSet( $query->getQuery() )->row;
			$this->assertEqual( $row['School_name'], 'Northwind' );
			$this->assertEqual( $row['name'], 'Science' );
			$this->assertEqual( $row['student_name'], 'Bobby' );
			$this->assertFalse( isset( $row['student_id'] ));

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->update( 'student' );
			$query->set( 'student', 'student_name', 'Sally' );
			$query->where( 'student', 'student_name', '=', 'Alan' );

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( 'student' )->rows[0]['student_name'], 'Bobby' );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->update( 'student' );
			$query->set( 'student', 'student_name', 'Sally' );
			$query->where( 'student', 'student_name', '=', 'Bobby' );

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( 'student' )->rows[0]['student_name'], 'Sally' );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->delete();
			$query->from( 'student' );
			$query->where( 'student', 'student_name', '=', 'Bobby' );

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( 'student' )->count, 1 );

			$query = \System\Base\ApplicationBase::getInstance()->dataAdapter->queryBuilder();

			$query->delete();
			$query->from( 'student' );
			$query->where( 'student', 'student_name', '=', 'Sally' );

			\System\AppServlet::getInstance()->dataAdapter->execute( $query->getQuery() );

			$this->assertEqual( $this->getRows( 'student' )->count, 0 );
		}

		function getRows( $table ) {
			return \System\AppServlet::getInstance()->dataAdapter->queryBuilder()->select()->from($table)->openDataSet();
		}
	}
?>