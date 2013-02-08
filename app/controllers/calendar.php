<?php
    namespace MyApp\Controllers;

	class Calendar extends \MyApp\ApplicationController {

		function onPageInit( &$page, $args ) {

			$this->page->add( new \Calendar\Calendar( 'calendar' ));
			$this->page->calendar->addEvent( 'Today', 'test event - today' );
			$this->page->calendar->addEvent( 'March 15, 2008', 'test event - march 15 2008' );
			$this->page->calendar->addEvent( date( 'F j, Y' ), 'test event - today2' );
			$this->page->calendar->onDayClick = '/day/%date%';
		}
	}
?>