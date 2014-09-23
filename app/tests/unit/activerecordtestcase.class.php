<?php
    namespace MyApp\Models;
    use System\DB\QueryBuilder;
    use System\DB\DataAdapter;
    use System\DB\DataSet;
    use \MyApp\Models\Team;
    use \MyApp\Models\Player;
    use \MyApp\Models\TablePage;
    use \MyApp\Models\School;
    use \MyApp\Models\Classrooms;
    use \MyApp\Models\Student;

	class ActiveRecordTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
			if( \System\AppServlet::getInstance()->dataAdapter instanceof \System\DB\MSSqlDataAdapter ) :
				$this->loadFixtures( 'mssql_school.sql' );
			else :
				$this->loadFixtures( 'mysql_school.sql' );
			endif;
		}

		function cleanup() {
		}

		function testGettersAndSetters() {
			$school = School::create( array( 'School_name' => 'noname' ));

			$school['School_name'] = 'northwind';
			$this->assertTrue( $school['School_name'] == 'northwind' );
			$this->assertTrue( $school->School_name == 'northwind' );

			$school['School_name'] = 'springfield';
			$this->assertTrue( $school['School_name'] == 'springfield' );
			$this->assertTrue( $school->School_name == 'springfield' );

			$school->School_name = 'south texas';
			$this->assertTrue( $school['School_name'] == 'south texas' );
			$this->assertTrue( $school->School_name == 'south texas' );

			$school->School_name = 'north texas';
			$this->assertTrue( $school['School_name'] == 'north texas' );
			$this->assertTrue( $school->School_name == 'north texas' );
		}

		function testStaticMethods() {
			$george  = Student::create( array( 'student_name' => 'george', 'student_age' => '18' ));
			$sally   = Student::create( array( 'student_name' => 'sally', 'student_age' => '15' ));
			$sally2  = Student::create( array( 'student_name' => 'sally', 'student_age' => '19' ));
			$michael = Student::create( array( 'student_name' => 'michael', 'student_age' => '19' ));

			$george->save();
			$sally->save();
			$sally2->save();
			$michael->save();

			// test ::count
			$this->assertEqual( Student::countAll(array('student_name'=>'george')), 1);
			$this->assertEqual( Student::countAll(), 4 );
			$this->assertEqual( Classrooms::countAll(), 0 );

			// test ::all
			$this->assertEqual( Student::all(array('student_name'=>'george'))->count, 1);
			$this->assertEqual( Student::all()->rows[0]['student_name'], 'george' );
			$this->assertEqual( Student::all()->rows[2]['student_name'], 'sally' );
			$this->assertEqual( Student::all()->count, 4 );
			$this->assertEqual( count(Student::all()->fields), 3 );
			$this->assertEqual( count(Classrooms::all()->fields), 3 );

			// test ::more
			$this->assertEqual( Student::more(array('student_name'=>'george'))->count, 1);
			$this->assertEqual( Student::more()->rows[0]['student_name'], 'george' );
			$this->assertEqual( Student::more()->rows[2]['student_name'], 'sally' );
			$this->assertEqual( Student::more()->count, 4 );
			$this->assertEqual( count(Student::more()->fields), 3 );
			$this->assertEqual( count(Classrooms::more()->fields), 5 );

			// test ::last
			$this->assertEqual( Student::last(array('student_name'=>'george'))->student_name, 'george');
			$this->assertEqual( Student::last()->student_name, 'michael' );

			// test ::first
			$this->assertEqual( Student::first(array('student_name'=>'sally'))->student_name, 'sally');
			$this->assertEqual( Student::first()->student_name, 'george' );

			// test ::all
			$this->assertEqual( Student::all(array('student_name'=>'george'))->count, 1);
			$this->assertEqual( Student::all()->rows[0]["student_name"], 'george' );
			$this->assertEqual( Student::all()->rows[2]["student_name"], 'sally' );
			$this->assertEqual( Student::all()->count, 4 );
			$this->assertEqual( count(Student::all()->fields), 3 );
		}

		function testCreateMethod() {
			$northwind = School::create( array( 'School_name' => 'northwind' ));
			$northwind->save();

			$science = $northwind->createClassrooms();
			$this->assertTrue($science instanceof Classrooms);
			$this->assertEqual($science['name'], '');
			$this->assertEqual($science['School_id'], $northwind->School_id);

			$math = $northwind->createClassrooms(array( 'name' => 'Math' ));
			$this->assertTrue($math instanceof Classrooms);
			$this->assertEqual($math['name'], 'Math');
			$this->assertEqual($math['School_id'], $northwind->School_id);

			$english = Classrooms::create();
			$this->assertTrue($english instanceof Classrooms);
			$this->assertNull($english['name']);
			$this->assertNull($english['School_id']);

			$history = Classrooms::create( array( 'name' => 'History' ));
			$this->assertTrue($history instanceof Classrooms);
			$this->assertEqual($history['name'], 'History');
			$this->assertNull($history['School_id']);
		}

		function testFindMethod() {
			$george  = Student::create( array( 'student_name' => 'george', 'student_age' => '18' ));
			$sally   = Student::create( array( 'student_name' => 'sally', 'student_age' => '15' ));
			$sally2  = Student::create( array( 'student_name' => 'sally', 'student_age' => '19' ));
			$michael = Student::create( array( 'student_name' => 'michael', 'student_age' => '19' ));

			$george->save();
			$sally->save();
			$sally2->save();
			$michael->save();

			$students = $this->getArray( 'student' );

			$this->assertTrue( $students[0]['student_name'] == 'george' );
			$this->assertTrue( $students[0]['student_age'] == 18 );
			$this->assertTrue( $students[1]['student_name'] == 'sally' );
			$this->assertTrue( $students[1]['student_age'] == 15 );
			$this->assertTrue( $students[2]['student_name'] == 'sally' );
			$this->assertTrue( $students[2]['student_age'] == 19 );
			$this->assertTrue( $students[3]['student_name'] == 'michael' );
			$this->assertTrue( $students[3]['student_age'] == 19 );

			$student = Student::find( array( 'student_name' => 'michael', 'student_age' => '19' ));
			$this->assertTrue( $student->student_name == 'michael' );
			$this->assertTrue( $student->student_age == 19 );

			$student = Student::find( array( 'student_name' => 'sally', 'student_age' => '19' ));
			$this->assertTrue( $student->student_name == 'sally' );
			$this->assertTrue( $student->student_age == 19 );

			$student = Student::find( array( 'student_name' => 'sally', 'student_age' => '15' ));
			$this->assertTrue( $student->student_name == 'sally' );
			$this->assertTrue( $student->student_age == 15 );

			$student = Student::find( array( 'student_name' => 'sally', 'student_age' => '18' ));
			$this->assertNull( $student );
		}

		function testAssociations() {
			$northwind = School::create( array( 'School_name' => 'northwind' ));
			$northwind->save();

			$this->assertEqual(count($northwind->associations), 1);
			$this->assertEqual($northwind->associations[0]['relationship'], 'has_many');
			$this->assertEqual($northwind->associations[0]['type'], 'MyApp\Models\Classrooms');
			$this->assertEqual($northwind->associations[0]['table'], 'classrooms');
			$this->assertEqual($northwind->associations[0]['columnRef'], 'School_id');
			$this->assertEqual($northwind->associations[0]['columnKey'], 'School_id');
			$this->assertFalse($northwind->associations[0]['notNull']);

			$science = $northwind->createClassrooms( array( 'name' => 'math' ));

			$this->assertEqual(count($science->associations), 2);
			$this->assertEqual($science->associations[0]['relationship'], 'belongs_to');
			$this->assertEqual($science->associations[0]['type'], 'MyApp\Models\School');
			$this->assertEqual($science->associations[0]['table'], 'classrooms');
			$this->assertEqual($science->associations[0]['columnRef'], 'School_id');
			$this->assertEqual($science->associations[0]['columnKey'], 'School_id');
			$this->assertFalse($science->associations[0]['notNull']);
			$this->assertEqual($science->associations[1]['relationship'], 'has_many_and_belongs_to');
			$this->assertEqual($science->associations[1]['type'], 'MyApp\Models\Student');
			$this->assertEqual($science->associations[1]['table'], 'student_classrooms');
			$this->assertEqual($science->associations[1]['columnRef'], 'student_id');
			$this->assertEqual($science->associations[1]['columnKey'], 'classroom_id');
			$this->assertTrue($science->associations[1]['notNull']);

			$mark = Student::create( array('student_name'=>'Mark'));

			$this->assertEqual(count($mark->associations), 1);
			$this->assertEqual($mark->associations[0]['relationship'], 'has_many_and_belongs_to');
			$this->assertEqual($mark->associations[0]['type'], 'MyApp\Models\Classrooms');
			$this->assertEqual($mark->associations[0]['table'], 'student_classrooms');
			$this->assertEqual($mark->associations[0]['columnRef'], 'classroom_id');
			$this->assertEqual($mark->associations[0]['columnKey'], 'student_id');
			$this->assertTrue($mark->associations[0]['notNull']);
		}

		function testMagicMethods() {
			$northwind = School::create( array( 'School_name' => 'Northwind' ));
			$northwind->save();
			$math = $northwind->createClassrooms( array( 'name' => 'Math' ));
			$math->save();
			$science = $northwind->createClassrooms( array( 'name' => 'Science' ));
			$science->save();
			$mark = Student::create(array('student_name'=>'Mark', 'student_age'=>17));
			$mark->save();
			$sally = Student::create(array('student_name'=>'Sally', 'student_age'=>16));
			$sally->save();
			$billy = Student::create(array('student_name'=>'Billy', 'student_age'=>16));
			$billy->save();
			$science->addStudent($sally);
			$science->addStudent($mark);
			$science->addStudent($billy);
			$math->addStudent($mark);

			// test ->getCount()
			$this->assertEqual($northwind->getCountClassrooms(), 2);
			$this->assertEqual($northwind->getCountClassrooms(array('name'=>'Math')), 1);
			$this->assertEqual($math->getCountStudents(), 1);
			$this->assertEqual($science->getCountStudents(), 3);
			$this->assertEqual($science->getCountStudents(array('student_age'=>16)), 2);

			// test ->getAll()
			$this->assertEqual($northwind->getAllClassrooms()->count, 2);
			$this->assertEqual($northwind->getAllClassrooms(array('name'=>'Math'))->count, 1);
			$this->assertEqual($math->getAllStudents()->count, 1);
			$this->assertEqual($science->getAllStudents()->count, 3);
			$this->assertEqual($science->getAllStudents(array('student_age'=>16))->count, 2);

			// test ->findAll()
			$this->assertEqual($northwind->findAllClassrooms()->count, 2);
			$this->assertEqual($northwind->findAllClassrooms(array('name'=>'Math'))->count, 1);
			$this->assertEqual($math->findAllStudents()->count, 1);
			$this->assertEqual($science->findAllStudents()->count, 3);

			// test ->findParent()
			$this->assertEqual($math->findParentSchool()->School_name, 'Northwind');

			// test ->find()
			$this->assertEqual($northwind->findClassrooms($science->classroom_id)->name, 'Science');
		}

		function testIsNull() {
			$northwind = School::create( array( 'School_name' => 'northwind' ));
			$this->assertTrue( $northwind->isEmpty );
			$northwind->save();
			$this->assertFalse( $northwind->isEmpty );

			$school1 = School::find( array( 'School_name' => 'northwind' ));
			$school2 = School::find( array( 'School_id' => 'void' ));

			$this->assertFalse( $school1->isEmpty );
			$this->assertFalse( $school1->isNull );
			$this->assertNull( $school2 );
		}

		function testFieldMappings() {
			$student = Student::create();

			$this->assertEqual($student->fields['student_name'], 'string');
			$this->assertEqual($student->fields['student_age'], 'numeric');
			$this->assertEqual(count($student->fields), 2);

			$this->assertEqual($student->rules['student_name'], array('length(0, 240)'));
			$this->assertEqual($student->rules['student_age'], array('numeric', 'length(0, 10)'));
			$this->assertEqual(count($student->rules), 3);
		}

		function testFishhook() {
			// test relationships to self (fishhook)
			$root = TablePage::create();
			$root->save();

			$childpage  = TablePage::create();
			$childpage2 = TablePage::create();

			$root->addTablePage( $childpage );
			$root->addTablePage( $childpage2 );

			$pages = $this->getArray( 'page' );
			$this->assertTrue( $pages[0]['page_id']   == 1 );
			$this->assertTrue( $pages[0]['parent_id'] === null );
			$this->assertTrue( $pages[1]['page_id']   == 2 );
			$this->assertTrue( $pages[1]['parent_id'] == 1 );
			$this->assertTrue( $pages[2]['page_id']   == 3 );
			$this->assertTrue( $pages[2]['parent_id'] == 1 );

			$children = $root->findAllTablePageRecords();

			$this->assertTrue( $children->count === 2 );
			$this->assertTrue( $children[0]->page_id       == 2 );
			$this->assertTrue( $children[1]->page_id       == 3 );

			$parent = $childpage->findParentTablePageRecord();
			$this->assertTrue( $parent->page_id            == 1 );

			$root->removeTablePage( $childpage2 );
			$pages = $this->getArray( 'page' );
			$this->assertTrue( $pages[2]['parent_id'] === NULL );

			$root->deleteAll();
			$pages = $this->getArray( 'page' );
			$this->assertTrue( sizeof( $pages )       == 1 );
			$this->assertTrue( $pages[0]['page_id']   == 3 );

			\System\AppServlet::getInstance()->dataAdapter->execute( 'delete from `page`' );
			$rs = \System\AppServlet::getInstance()->dataAdapter->openDataSet( 'select * from page' );
			$rs['page_id'] = 1;
			$rs['parent_id'] = 0;
			$rs->insert();

			$page1_id = $rs['page_id'];

			$rs['page_id'] = 2;
			$rs['parent_id'] = $page1_id;
			$rs->insert();

			$page2_id = $rs['page_id'];

			$rs['page_id'] = 3;
			$rs['parent_id'] = $page1_id;
			$rs->insert();

			$page3_id = $rs['page_id'];

			$rs['page_id'] = 4;
			$rs['parent_id'] = $page3_id;
			$rs->insert();

			$page4_id = $rs['page_id'];

			$page = TablePage::find( array( 'page_id' => $page1_id ));
			$page->removeAllTablePageRecords();

			$pages = $this->getArray( 'page' );

			$this->assertEqual( $pages[0]['parent_id'], 0 );
			$this->assertEqual( $pages[1]['parent_id'], 0 );
			$this->assertEqual( $pages[2]['parent_id'], 0 );
			$this->assertEqual( $pages[3]['parent_id'], $page3_id );

			// should be exception
			$this->expectException();
			$page->addTablePage( TablePage::find( array( 'page_id' => $page4_id )));

			$pages = $this->getArray( 'page' );

			$this->assertTrue( $pages[0]['parent_id'] == 0 );
			$this->assertTrue( $pages[1]['parent_id'] == 0 );
			$this->assertTrue( $pages[2]['parent_id'] == 0 );
			$this->assertTrue( $pages[3]['parent_id'] == $page3_id );
		}

		function testRecursive() {
			// test relationships to objects with relationships to self
			// (each object has 1 to many with each other)

			$blueteam = Team::create();
			$blueteam->save();

			$redteam = Team::create();
			$redteam->save();

			$bill = Player::create();
			$bill->save();

			$bob = Player::create();
			$bob->save();

			$bob->addTeamRecord( $blueteam );
			$bob->addTeam( $redteam );
			$blueteam->addPlayer( $bill );
			$redteam->addPlayer( $bob );

			$teams   = $this->getArray( 'team' );
			$players = $this->getArray( 'player' );

			$this->assertTrue( $teams[0]['player_id'] == 2 );
			$this->assertTrue( $teams[1]['player_id'] == 2 );
			$this->assertTrue( $players[0]['team_id'] == 1 );
			$this->assertTrue( $players[1]['team_id'] == 2 );

			$this->assertNull( $bob->removeTeamRecord( $blueteam ));
			$this->assertNull( $bill->addTeam( $blueteam ));
			$this->assertNull( $blueteam->removePlayer( $bill ));

			$teams   = $this->getArray( 'team' );
			$players = $this->getArray( 'player' );
			$this->assertTrue( $teams[0]['player_id'] == 1 );
			$this->assertTrue( $teams[1]['player_id'] == 2 );
			$this->assertTrue( $players[0]['team_id'] == 0 );
			$this->assertTrue( $players[1]['team_id'] == 2 );
		}

		function testScenerios() {
			// test record creation
			$northwind = School::create( array( 'School_name' => 'northwind' ));
			$northwind->save();

			$ridgewood = School::create( array( 'School_name' => 'ridgewood' ));
			$ridgewood->save();

			$schools = $this->getArray( 'School' );

			$this->assertTrue( $schools[0]['School_name'] == 'northwind' );
			$this->assertTrue( $schools[1]['School_name'] == 'ridgewood' );

			// test adding associations 1 to many
			$art     = Classrooms::create( array( 'name' => 'art' ));
			$science = Classrooms::create( array( 'name' => 'science' ));
			$math    = Classrooms::create( array( 'name' => 'math' ));

			$this->assertNull( $northwind->addClassrooms( $art ));

			$ridgewood->addClassrooms( $science );
			$ridgewood->addClassroomsRecord( $math );

			$classrooms = $this->getArray( 'classrooms' );

			$this->assertTrue( $classrooms[0]['name'] == 'art' );
			$this->assertTrue( $classrooms[1]['name'] == 'science' );
			$this->assertTrue( $classrooms[0]['School_id']      == 1 );
			$this->assertTrue( $classrooms[1]['School_id']      == 2 );

			// test adding associations many to many
			$sam     = Student::create( array( 'student_name' => 'sam', 'student_age' => '14' ));
			$sally   = Student::create( array( 'student_name' => 'sally', 'student_age' => 16 ));
			$george  = Student::create( array( 'student_name' => 'george', 'student_age' => 17 ));
			$ralph   = Student::create( array( 'student_name' => 'ralph', 'student_age' => 14 ));

			$this->assertTrue( $sam->isEmpty );
			$this->assertTrue( $sally->isEmpty );
			$this->assertTrue( $george->isEmpty );
			$this->assertTrue( $ralph->isEmpty );

			$sam->save();
			$sally->save();
			$george->save();
			$ralph->save();

			$this->assertFalse( $sam->isEmpty );
			$this->assertFalse( $sally->isEmpty );
			$this->assertFalse( $george->isEmpty );
			$this->assertFalse( $ralph->isEmpty );

			$sam->addClassrooms( $art );
			$sally->addClassrooms( $art );
			$science->addStudent( $george );
			$math->addStudent( $george );

			$students = $this->getArray( 'student' );
			$this->assertTrue( $students[3]['student_name']     == 'ralph' );

			$student_classrooms = $this->getArray( 'student_classrooms' );
			$this->assertTrue( $student_classrooms[0]['student_id']      == 1 );
			$this->assertTrue( $student_classrooms[0]['classroom_id']    == 1 );

			$this->assertTrue( $student_classrooms[1]['student_id']      == 2 );
			$this->assertTrue( $student_classrooms[1]['classroom_id']    == 1 );

			$this->assertTrue( $student_classrooms[2]['student_id']      == 3 );
			$this->assertTrue( $student_classrooms[2]['classroom_id']    == 2 );

			$this->assertTrue( $student_classrooms[3]['student_id']      == 3 );
			$this->assertTrue( $student_classrooms[3]['classroom_id']    == 3 );

			// test _findByProperty
			$student = Student::find( array( 'student_name' => 'sally' ));
			$this->assertTrue( $student->student_name == 'sally' );

			// test _getAllByType 1 to many and many to many
			$classes = $george->findAllClassrooms();
			$this->assertEqual( $classes->count, 2 );
			$this->assertEqual( Classrooms::countAll(), 3 );
			$this->assertEqual( $george->getCountClassrooms(), 2 );

			$this->assertTrue( $classes[0]->name   == 'science' );
			$this->assertTrue( $classes[1]->name   == 'math' );

			$classes = $northwind->findAllClassrooms();
			$this->assertTrue( sizeof( $classes )  == 1 );
			$this->assertTrue( $classes[0]->name   == 'art' );

			// test _getByowner
			$School = $science->findParentSchool();
			$this->assertTrue( $School->School_name       == 'ridgewood' );

			// test removeAss
			$george->removeAllClassroomsRecords();
			$student_classrooms = $this->getArray( 'student_classrooms' );
			$this->assertTrue( !isset( $student_classrooms[2] ));
			$this->assertTrue( !isset( $student_classrooms[3] ));

			$northwind->removeAllClassrooms();
			$classrooms = $this->getArray( 'classrooms' );
			$this->assertTrue( $classrooms[0]['classroom_id']    == 1 );
			$this->assertTrue( $classrooms[0]['School_id']       == 0 );

			// test deleteAll
			$art = Classrooms::find( array( 'name' => 'art' ));
			$School = School::find( array( 'School_name' => 'northwind' ));

			$school1 = School::find( array( 'School_name' => 'foo' ));
			$school2 = School::find( array( 'School_id' => 'bar' ));

			$this->assertFalse( $School->isNull );
			$this->assertFalse( $School->isEmpty );
			// $this->assertTrue( $school1->isNull );
			// $this->assertTrue( $school2->isNull );
			$this->assertNull( $school1 );
			$this->assertNull( $school2 );

			$School->addClassrooms( $art );
			$School->deleteAll();

			$classrooms = $this->getArray( 'classrooms' );
			$this->assertTrue( $classrooms[0]['classroom_id']    == 2 );
			$this->assertTrue( sizeof( $classrooms )             == 2 );

			$student_classrooms = $this->getArray( 'student_classrooms' );
			$this->assertTrue( sizeof( $student_classrooms )     == 0 );

			$schools = $this->getArray( 'School' );
			$this->assertTrue( $schools[0]['School_id']          == 2 );
			$this->assertTrue( sizeof( $schools )                == 1 );
		}

		function getArray( $table ) {
			return \System\AppServlet::getInstance()->dataAdapter->queryBuilder()
			->select( '*' )
			->from( $table )->openDataSet()->rows;
		}
	}
?>