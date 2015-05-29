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
			$this->assertResponse( 'of 2' );
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( '<tr class="row" onclick' );

			$this->assertResponse( '<td data-field="Company" class="company_class"><a href="mailto:moore@adobe.com">Adobe</a></td>' );
			$this->assertResponse( 'onclick="Rum.evalAsync(\'/test/public/\',\''.\Rum::config()->requestParameter.'=gridview&amp;Company=Microsoft' );
			$this->assertResponse( '<td class="company_class">CompanyFooter</td>' );

			$this->assertResponse( '<th data-field="Company" class="company_class">' );
			$this->assertResponse( '<td data-field="Company" class="company_class">' );
			$this->assertResponse( '<td class="company_class">' );
			$this->assertResponse( '<tbody><tr class="row"><td data-field="Company" class="company_class">' );

			// test selected
			$this->assertResponse( 'onclick="if(this.checked)this.checked=false;else this.checked=true;" id="page_table1__item_' );
		}

		function testGetURL() {
			$this->get( array( 'page' => 'gridview' ));
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( 'of 2' );
			$this->assertResponse( 'next' );
		}

		function testAjaxURL() {
			$this->post( array( 'page' => 'gridview', 'Company' => 'Apple', 'Edit'=>'Edit', 'async' => '1' ));

			$this->assertMessage( 'Apple Clicked' );
		}

		function testSorting() {
			$this->get( array( 'page' => 'gridview', 'page_table1__page' => '2', 'page_table1__sort_by' => 'Contact', 'page_table1__sort_order' => 'desc' ));

			$this->assertResponse( 'Joe Wilson' );
			$this->assertResponse( 'Aaron Moore' );
			$this->assertResponse( 'type="number" value="2"' );
			$this->assertResponse( 'of 2' );
			$this->assertResponse( 'prev' );

			$this->get( array( 'page' => 'gridview', 'page_table2__page' => '1', 'page_table1__sort_by' => 'ContactPhone', 'page_table1__sort_order' => 'desc' ));
			$this->assertResponse( 'gridview/?page=gridview&amp;page_table2__page=1&amp;page_table1__sort_by=ContactPhone&amp;page_table1__sort_order=desc&amp;page_table2__sort_by=Company&amp;page_table2__sort_order=asc' );
		}

		function testPaging() {
			$this->get( array( 'page' => 'gridview', 'page_table1__page' => '2', 'page_table1__sort_by' => 'Contact', 'page_table1__sort_order' => 'desc' ));
			$this->assertResponse( 'type="number" value="2"' );
			$this->assertResponse( 'of 2' );
			$this->assertResponse( 'prev' );
		}

		function testFiltering() {
			$this->get(array('id'=>'7'));

//			$this->assertResponse( 'Rum.evalAsync(\'/test/public\',\'id=7&amp;'.\Rum::config()->requestParameter.'=gridview&amp;page_table1_Company__filter_value=\'+encodeURIComponent(this.value)' );
			
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( 'of 2' );
			$this->assertEqual($this->controller->table1->dataSource->count, 18);

			$this->get(array('page_table1_Company__filter_value'=>'a'));
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( 'of 1' );
			$this->assertResponse( 'page_table1_Company__filter_value" value="a"');
			$this->assertResponse( 'page_table1_Company__filter_value=a&amp;path=gridview&amp;page_table1_Company__filter_valu');
			$this->assertResponse( 'page_table1_Company__filter_value=a&amp;path=gridview&amp;page_table1_Company__filter_value=\'+encodeURIComponent(this.value');

			$this->get(array('page_table1_Company__filter_value'=>'a', 'page_table1_Objective__filter_value'=>'Marketing Relationship'));
			$this->assertResponse( 'name="page_table1_Objective__filter_value" value="Marketing Relationship"');
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( 'of 1' );

			$this->get(array('page_table1_Objective__filter_value'=>'Marketing Relationship', 'page_table1_Company__filter_value'=>''));
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( 'of 1' );

			$this->get();
			$this->assertResponse( 'type="number" value="1"' );
			$this->assertResponse( 'of 2' );
		}
	}
?>