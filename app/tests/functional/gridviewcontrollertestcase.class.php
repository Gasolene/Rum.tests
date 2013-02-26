<?php
    namespace MyApp\Controllers;

	class GridViewControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testOnLoad() {
			$this->get();

			$this->assertResponse( '?page_table2__page=1&amp;page_table2__sort_by=Company&amp;page_table2__sort_order=asc">Company</a>' );
			$this->assertResponse( '?page_table2__page=1&amp;page_table2__sort_by=ContactPhone&amp;page_table2__sort_order=asc">Contact Phone</a>' );
			$this->assertResponse( '>showing 1 to 10 of 18</span>' );
			$this->assertResponse( '<tr class="row" id="page_table1__0" onclick' );

			$this->assertResponse( '<td class="company_class"><a href="mailto:moore@adobe.com">Adobe</a></td>' );
			$this->assertResponse( 'onclick="PHPRum.sendPostBack(\'/test/public\', \'page=gridview&amp;Company=Microsoft\', \'POST\');"' );
			$this->assertResponse( '<td class="company_class">CompanyFooter</td>' );

			$this->assertResponse( '<th class="company_class">' );
			$this->assertResponse( '<tfoot><tr class="footer"><td class="company_class">' );
			$this->assertResponse( '<tbody><tr class="row" id="page_table2__0"><td class="company_class">' );

			// test selected
			$this->assertResponse( 'onclick="if(this.checked)this.checked=false;else this.checked=true;" id="page_table1__item_' );
			$this->assertResponse( 'class="page_table1__checkbox" />' );
		}

		function testGetURL() {
			$this->get( array( 'page' => 'gridview', 'page_table1__page' => '2', 'page_table1__sort_by' => 'Contact', 'page_table1__sort_order' => 'desc' ));

			//$this->assertResponse( '<td>Amazon.com</td>      <td>Marketing relationship</td>      <td>Joe Wilson</td>      <td>jwilson@amazon.com</td>      <td>206-622-0708</td>      <td>3/12/2005</td>      <td>Meeting</td>' );
			$this->assertResponse( '>showing 11 to 18 of 18</span>' );
			$this->assertResponse( 'prev' );
		}

		function testPostURL() {
			$this->post( array( 'page' => 'gridview', 'Company' => 'Apple' ));

			$this->assertMessage( 'Apple Clicked' );
		}

		function testFilters() {
			$this->get(array('id'=>'7'));

			$this->assertResponse( 'PHPRum.sendPostBack(\'/test/public\', \'id=7&amp;page=gridview&amp;page_table1__filter_name=Company&amp;page_table1__filter_value=\'+this.value' );
			$this->assertResponse( 'showing 1 to 10 of 18' );
			$this->assertEqual($this->controller->table1->dataSource->count, 18);

			$this->get(array('page_table1__filter_name'=>'Company', 'page_table1__filter_value'=>'a'));
			$this->assertResponse( 'showing 1 to 10 of 10' );
			$this->assertResponse( '<input name="page_table1__filter_value" class="textbox" value="a" onchange="');

			$this->get(array('page_table1__filter_name'=>'Objective', 'page_table1__filter_value'=>'Marketing Relationship'));
			$this->assertResponse( '<input name="page_table1__filter_value" class="textbox" value="a" onchange="');
			//$this->assertResponse( '<select name="page_table1__filter_value" onchange="');
			//$this->assertResponse( '<option value="Marketing relationship" selected="selected">Marketing relationship</option>');
			$this->assertResponse( 'showing 1 to 3 of 3' );

			$this->get(array('page_table1__filter_name'=>'Company', 'page_table1__filter_value'=>''));
			$this->assertResponse( '<input name="page_table1__filter_value" class="textbox" onchange="');
			//$this->assertResponse( '<select name="page_table1__filter_value" onchange="');
			//$this->assertResponse( '<option value="Marketing relationship" selected="selected">Marketing relationship</option>');
			$this->assertResponse( 'showing 1 to 4 of 4' );

			$this->get();
			$this->assertResponse( '<input name="page_table1__filter_value" class="textbox" onchange="');
			//$this->assertResponse( '<select name="page_table1__filter_value" onchange="');
			//$this->assertResponse( '<option value="Marketing relationship" selected="selected">Marketing relationship</option>');
			$this->assertResponse( 'showing 1 to 4 of 4' );
		}
	}
?>