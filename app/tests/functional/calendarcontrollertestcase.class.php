<?php
    namespace MyApp\Controllers;

	class CalendarControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testOnLoad() {
			$this->get();

			$this->assertResponse( '<span>' . (date('M, Y',time())) . '</span>' );
			$this->assertResponse( '<a href="/day/'.(date('Y-n-j',time())).'">'.(date('j',time())).' <div class="details">test event - today<br />test event - today2<br /></div></a>' );
		}

		function testGetURL() {
			$this->get( array( 'page' => 'calendar', 'page_calendar__month' => '3', 'page_calendar__year' => '2008' ));

			$this->assertResponse( '<span>Mar, 2008</span>' );
			$this->assertResponse( '<a href="/day/2008-3-15">15 <div class="details">test event - march 15 2008' );
			$this->assertResponse( '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td class="day"><a href="/day/2008-3-1">1</a>' );
			$this->assertResponse( '<td class="day"><a href="/day/2008-3-31">31</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>' );
			$this->assertResponse( 'page_calendar__month=2&amp;page_calendar__year=2008' );
			$this->assertResponse( 'page_calendar__month=4&amp;page_calendar__year=2008' );
		}
	}
?>