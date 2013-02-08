<?php
    namespace MyApp\Controllers;

	class ReportControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testGet() {
			$this->get();

			$this->assertResponse( '<table class="datareport"><caption>report</caption><thead><tr><th>Name</th><th>Test</th><th>Score</th></tr></thead><tfoot></tfoot><tbody></tbody></table>' );
			//$this->assertResponse( '<table class="datareport" id="page_report"><caption>report</caption><thead><tr><th style="width:50%">Score</th></tr></thead><tfoot></tfoot><tbody><tr class="group_header"></tr><tr class="group_header"><th colspan="3">Name : &quot;Michael&quot;</th></tr><tr class="group_header"><th colspan="3">Test : &quot;test&quot;</th></tr><tr class="group_detail"><td>44</td></tr><tr class="group_footer"><td></td></tr><tr class="group_footer"><td></td></tr><tr class="group_header"><th colspan="3">Name : &quot;John&quot;</th></tr><tr class="group_header"><th colspan="3">Test : &quot;test&quot;</th></tr><tr class="group_detail"><td>94</td></tr><tr class="group_detail"><td>64</td></tr><tr class="group_footer"><td></td></tr><tr class="group_footer"><td></td></tr><tr class="group_header"><th colspan="3">Name : &quot;Joe&quot;</th></tr><tr class="group_header"><th colspan="3">Test : &quot;test2&quot;</th></tr><tr class="group_detail"><td>70</td></tr><tr class="group_footer"><td></td></tr><tr class="group_footer"><td></td></tr><tr class="group_header"><th colspan="3">Name : &quot;Jane&quot;</th></tr><tr class="group_header"><th colspan="3">Test : &quot;test&quot;</th></tr><tr class="group_detail"><td>57</td></tr><tr class="group_footer"><td></td></tr><tr class="group_header"><th colspan="3">Test : &quot;test2&quot;</th></tr><tr class="group_detail"><td>34</td></tr><tr class="group_detail"><td>12</td></tr><tr class="group_footer"><td></td></tr><tr class="group_footer"><td></td></tr><tr class="group_header"><th colspan="3">Name : &quot;Bill&quot;</th></tr><tr class="group_header"><th colspan="3">Test : &quot;test&quot;</th></tr><tr class="group_detail"><td>81</td></tr><tr class="group_detail"><td>59</td></tr><tr class="group_footer"><td></td></tr><tr class="group_header"><th colspan="3">Test : &quot;test2&quot;</th></tr><tr class="group_detail"><td>66</td></tr><tr class="group_footer"><td></td></tr><tr class="group_footer"><td></td></tr><tr class="group_footer"><td></td></tr></tbody></table>' );
		}
	}
?>