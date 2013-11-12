<?php
    namespace MyApp\Controllers;

	class GridViewControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testOnLoad() {
			$this->expectError();
			$this->expectError();
			$this->get();

			$this->assertResponse( '?page_table2__page=1&amp;page_table2__sort_by=Company&amp;page_table2__sort_order=asc">Company</a>' );
			$this->assertResponse( '?page_table2__page=1&amp;page_table2__sort_by=ContactPhone&amp;page_table2__sort_order=asc">Contact Phone</a>' );
			$this->assertResponse( '>showing 1 to 10 of 18</span>' );
			$this->assertResponse( '<tr class="row" id="page_table1__0" onclick' );

			$this->assertResponse( '<td class="company_class"><a href="mailto:moore@adobe.com">Adobe</a></td>' );
			$this->assertResponse( 'onclick="Rum.evalAsync(\'/test/public/\',\''.\Rum::config()->requestParameter.'=gridview&amp;Company=Microsoft' );
			$this->assertResponse( '<td class="company_class">CompanyFooter</td>' );

			$this->assertResponse( '<th class="company_class">' );
			$this->assertResponse( '<td class="company_class">' );
			$this->assertResponse( '<tbody><tr class="row" id="page_table2__0"><td class="company_class">' );

			// test selected
			$this->assertResponse( 'onclick="if(this.checked)this.checked=false;else this.checked=true;" id="page_table1__item_' );
		}

		function testGetURL() {
			$this->expectError();
			$this->expectError();
			$this->get( array( 'page' => 'gridview' ));
			$this->assertResponse( '>showing 1 to 10 of 18</span>' );
			$this->assertResponse( 'next' );
		}

		function xtestPostURL() {
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page' => 'gridview', 'Company' => 'Apple' ));

//			$this->assertMessage( 'Apple Clicked' );
		}

		function testAjaxURL() {
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page' => 'gridview', 'Company' => 'Apple', 'Edit'=>'Edit', 'async' => '1' ));

			$this->assertMessage( 'Apple Clicked' );
		}

		function testSorting() {
			$this->expectError();
			$this->expectError();
			$this->get( array( 'page' => 'gridview', 'page_table1__page' => '2', 'page_table1__sort_by' => 'Contact', 'page_table1__sort_order' => 'desc' ));

			$this->assertResponse( 'Joe Wilson' );
			$this->assertResponse( 'Aaron Moore' );
			$this->assertResponse( '>showing 11 to 18 of 18</span>' );
			$this->assertResponse( 'prev' );

			$this->expectError();
			$this->expectError();
			$this->get( array( 'page' => 'gridview', 'page_table2__page' => '1', 'page_table1__sort_by' => 'ContactPhone', 'page_table1__sort_order' => 'desc' ));
			$this->assertResponse( 'gridview/?page=gridview&amp;page_table2__page=1&amp;page_table1__sort_by=ContactPhone&amp;page_table1__sort_order=desc&amp;page_table2__sort_by=Company&amp;page_table2__sort_order=asc' );
		}

		function testPaging() {
			$this->expectError();
			$this->expectError();
			$this->get( array( 'page' => 'gridview', 'page_table1__page' => '2', 'page_table1__sort_by' => 'Contact', 'page_table1__sort_order' => 'desc' ));
			$this->assertResponse( '>showing 11 to 18 of 18</span>' );
			$this->assertResponse( 'prev' );
		}

		function testFiltering() {
			$this->expectError();
			$this->expectError();
			$this->get(array('id'=>'7'));

			$this->assertResponse( 'Rum.evalAsync(\'/test/public\',\'id=7&amp;'.\Rum::config()->requestParameter.'=gridview&amp;page_table1_Company__filter_value=\'+this.value)' );
			$this->assertResponse( 'showing 1 to 10 of 18' );
			$this->assertEqual($this->controller->table1->dataSource->count, 18);

			$this->expectError();
			$this->expectError();
			$this->get(array('page_table1_Company__filter_value'=>'a'));
			$this->assertResponse( 'showing 1 to 10 of 10' ); 
			$this->assertResponse( 'name="page_table1_Company__filter_value" value="a" title="Enter a string and press enter"');
			$this->assertResponse( 'onchange="Rum.evalAsync(\'/test/public\',\'page_table1_Company__filter_value=a&amp;path=gridview&amp;page_table1_Company__filter_value=\'+this.value);"');
			$this->assertResponse( 'onkeypress="if(event.keyCode==13){event.returnValue=false;Rum.evalAsync(\'/test/public\',\'page_table1_Company__filter_value=a&amp;path=gridview&amp;page_table1_Company__filter_value=\'+this.value);return false;}');

			$this->expectError();
			$this->expectError();
			$this->get(array('page_table1_Company__filter_value'=>'a', 'page_table1_Objective__filter_value'=>'Marketing Relationship'));
			$this->assertResponse( 'name="page_table1_Objective__filter_value" value="Marketing Relationship" title="Enter a string and press enter" onchange="Rum.evalAsync');
			$this->assertResponse( 'showing 1 to 3 of 3' );

			$this->expectError();
			$this->expectError();
			$this->get(array('page_table1_Objective__filter_value'=>'Marketing Relationship', 'page_table1_Company__filter_value'=>''));
			$this->assertResponse( 'showing 1 to 4 of 4' );

			$this->expectError();
			$this->expectError();
			$this->get();
			$this->assertResponse( 'showing 1 to 10 of 18' );
		}
	}
?>