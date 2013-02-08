<?php
    namespace MyApp\Controllers;

	class Schedule extends \MyApp\ApplicationController {

		function onPageInit( &$page, $args ) {
			return;

			$this->page->add( new \ScheduleView\ScheduleView( 'schedule' ));
			$this->page->schedule->addEvent( '2000-01-03 12:00:00', '2000-01-01 1:00:00', 'Test 1' );
			$this->page->schedule->addEvent( '2000-01-01 12:00:00', '2000-01-01 1:00:00', 'Test 2' );
			$this->page->schedule->addEvent( '2000-01-01 1:00:00',  '2000-01-01 2:00:00', 'Test 3' );
			$this->page->schedule->addEvent( '2000-01-01 2:00:00',  '2000-01-01 3:00:00', 'Test 4' );
			$this->page->schedule->addEvent( '2000-01-02 12:00:00', '2000-01-01 1:00:00', 'Test 5' );
		}
	}
?>