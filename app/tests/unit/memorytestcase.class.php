<?php
    namespace MyApp\Models;

	define( '__TEST_COUNT__', 100 ); // 100 loops
	define( '__LOSS_MARGIN__', 1024 * 100 ); // 100KB

	class MemoryTestCase extends \System\Testcase\UnitTestCaseBase {

		protected $enabled = false;

		function prepare() {
			if( \System\AppServlet::getInstance()->dataAdapter instanceof MSSqlDataAdapter ):
				$this->loadFixtures( 'mssql_school.sql' );
			else :
				$this->loadFixtures( 'mysql_school.sql' );
			endif;

			if(isset($_GET['memtest'])) $this->enabled = true;
		}

		function cleanup() {
		}

		function testCreateActiveRecord() {
			if(!$this->enabled) return;

			$array = array();

			$this->trap();
			for( $i = 0; $i < __TEST_COUNT__; $i++ ) {
				$array[$i] = Data\Classrooms::create( array( 'name' => 'test' ));
				$array[$i]->save();
			}
			$alloc = $this->alloc();

			$this->trap();
			for( $i = 0; $i < __TEST_COUNT__; $i++ ) {
				unset( $array[$i] );
			}
			$unalloc = $this->alloc();

			$this->output( $alloc, $unalloc );
		}

		function testUpdateActiveRecord() {
			if(!$this->enabled) return;

			$array = array();

			for( $i = 0; $i < __TEST_COUNT__; $i++ ) {
				$array[$i] = Data\Classrooms::create( array( 'name' => 'test' ));
				$array[$i]->save();
			}

			$this->trap();
			for( $i = 0; $i < __TEST_COUNT__; $i++ ) {
				$array[$i]->save();
			}
			$alloc = $this->alloc();

			$this->output( $alloc );
		}

		function testQueryActiveRecord() {
			if(!$this->enabled) return;

			$array = array();
			$school = Data\School::create( array( 'School_name' => 'test' ));
			$school->save();

			for( $i = 0; $i < __TEST_COUNT__; $i++ ) {
				$array[$i] = Data\Classrooms::create( array( 'name' => 'test' ));
				$array[$i]->save();
				$school->addClassrooms( $array[$i] );
			}

			$this->trap();
			for( $i = 0; $i < __TEST_COUNT__; $i++ ) {
				$array[$i]->getParentSchoolRecord();
			}
			$alloc = $this->alloc();

			$this->output( $alloc );
		}

		private function output( $alloc, $unalloc = 0 ) {

			if($alloc > 0)
			{
				$perc = number_format((( $alloc + $unalloc ) / $alloc ) * 100, 2 );
			}
			else
			{
				$perc = 0;
			}
			$backtrace = debug_backtrace();
			$function  = $backtrace[1]['class'] . '::' . $backtrace[1]['function'];

			echo "<pre>";
			echo "<span style=\"font-weight:bold\">$function()</span>\n";
			echo "total memory allocated: {$alloc} bytes (" . (int)( $alloc / 1024 ) . " kilobytes)\n";
			echo "total memory unallocated: ".-$unalloc." bytes (" . (int)( -$unalloc / 1024 ) . " kilobytes)\n";
			echo "total memory leaked: " . ( $alloc + $unalloc ) . " bytes (" . (int)(( $alloc + $unalloc ) / 1024 ) . " kilobytes)\n";
			if( $unalloc ) {
				echo "percentage memory leaked: " . $perc . "%\n";
			}
			echo "</pre>";

			$this->assertWithinMargin( $alloc, -$unalloc, __LOSS_MARGIN__ );
		}

		private function trap() {
			$GLOBALS['test_memory'] = memory_get_usage( TRUE );
		}

		private function alloc() {
			if( isset( $GLOBALS['test_memory'] )) {
				$alloc = ( memory_get_usage( TRUE ) - $GLOBALS['test_memory'] );
				unset( $GLOBALS['test_memory'] );
				return $alloc;
			}
			throw new Exception("No trapping");
		}
	}
?>