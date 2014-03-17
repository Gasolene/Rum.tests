<?php
    namespace MyApp\Controllers;

	class GridView2ControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		protected $fixtures = 'scaffolding.sql';

		function prepare() {
		}

		function cleanup() {
		}

		function testOnLoad() {
			$this->expectError();
			$this->get();

			$this->assertResponse( 'gridview2/?page_table__page=1&amp;page_table__sort_by=category_id&amp;page_table__sort_order=asc' );
			$this->assertResponse( '>showing 1 to 4 of 12</span>' );

			$this->assertResponse( 'onchange="Rum.evalAsync(\'/test/public/\',\'path=gridview2&amp;page_table_customer_name__filter_value=\'+encodeURIComponent(this.value),\'POST\',null,null' );
//			$this->assertResponse( 'onkeypress="if(event.keyCode==13){event.returnValue=false;blur();Rum.sendSync(\'/test/public\',\'path=gridview2&amp;page_table_customer_name__filter_value=\'+encodeURIComponent(this.value)' );

			// test selected
			$this->assertResponse( '<option value="2" selected="selected">Disabled</option>' );
		}

		function testGetURL() {
			$this->expectError();
			$this->get( array('page_table__page'=>'2'));
			$this->assertResponse( '>showing 5 to 8 of 12</span>' );
			$this->assertResponse( 'next' );
		}

		function testPostURL() {
		}

		function testSorting() {
			$this->expectError();
			$this->get( array( 'path' => 'gridview2', 'page_table__page' => '2', 'page_table__sort_by' => 'customer_name', 'page_table__sort_order' => 'desc' ));

			$this->assertResponse( 'Jane Doe' );
			$this->assertResponse( 'James' );
			$this->assertResponse( 'Greg' );
			$this->assertResponse( 'George' );
			$this->assertResponse( '>showing 5 to 8 of 12</span>' );
			$this->assertResponse( 'gridview2/?page_table__page=2&amp;page_table__sort_by=customer_name&amp;page_table__sort_order=asc' );

			$this->expectError();
			$this->get( array( 'path' => 'gridview2', 'page_table__page' => '3', 'page_table__sort_by' => 'customer_phone', 'page_table__sort_order' => 'asc' ));
			$this->assertResponse( 'Greg' );
			$this->assertResponse( 'Janet' );
			$this->assertResponse( 'Jane Doe' );
			$this->assertResponse( 'John Doe' );
			$this->assertResponse( '>showing 9 to 12 of 12</span>' );
			$this->assertResponse( 'gridview2/?page_table__page=3&amp;page_table__sort_by=customer_name&amp;page_table__sort_order=asc' );
			$this->assertResponse( 'gridview2/?page_table__page=3&amp;page_table__sort_by=customer_phone&amp;page_table__sort_order=desc' );
		}

		function testPaging() {
			$this->expectError();
			$this->get( array( 'path' => 'gridview2', 'page_table__page' => '2', 'page_table__sort_by' => 'customer_name', 'page_table__sort_order' => 'desc' ));
			$this->assertResponse( '>showing 5 to 8 of 12</span>' );
			$this->assertResponse( 'prev' );
		}

		function testFiltering() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get(array('page_table_customer_name__filter_value'=>'g'));
			$this->assertResponse( 'name="page_table_customer_name__filter_value" value="g"' );
			$this->assertResponse( 'Rum.evalAsync(\'/test/public/\',\'page_table_customer_name__filter_value=g&amp;path=gridview2&amp;page_table_customer_name__filter_value=\'+encodeURIComponent(this.value),\'POST\',null,null);' );
			$this->assertResponse( 'page_table_customer_name__filter_value=g&amp;path=gridview2&amp;page_table_category_id__filter_value=\'+encodeURIComponent(this.value' );
			$this->assertResponse( 'Greg' );
			$this->assertResponse( 'Geff' );
			$this->assertResponse( 'George' );
			$this->assertResponse( 'showing 1 to 3 of 3' );
			$this->assertEqual($this->controller->table->dataSource->count, 3);

			$this->expectError();
			$this->expectError();
			$this->get(array('page_table_customer_name__filter_value'=>'eG'));
			$this->assertResponse( 'showing 1 to 1 of 1' );
			$this->assertResponse( 'Greg' );
			$this->assertResponse( 'page_table_customer_name__filter_value=eG&amp;path=gridview2&amp;page_table_category_id__filter_value=\'+encodeURIComponent(this.value)' );
			$this->assertResponse( 'gridview2/?page_table_customer_name__filter_value=eG&amp;page_table__page=1&amp;page_table__sort_by=category_id&amp;page_table__sort_order=asc' );

			$this->expectError();
			$this->expectError();
			$this->get(array('page_table_customer_name__filter_value'=>'a','page_table_customer_phone__filter_value'=>'403'));
			$this->assertResponse( 'Jane Doe' );
			$this->assertResponse( 'showing 1 to 1 of 1' );

			$this->expectError();
			$this->get();
			$this->assertResponse( 'showing 1 to 4 of 12' );
		}

		function testState() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get(array(
				'page_table__page'=>'1',
				'page_table__sort_by'=>'customer_phone',
				'page_table__sort_order'=>'desc',
				'page_table_customer_phone__filter_value'=>'403',
				'page_table_customer_active__filter_value'=>'false',
				));

			$this->assertResponse( 'showing 1 to 1 of 1' );
			$this->assertResponse( 'Jane Doe' );
			$this->assertResponse( 'gridview2/?page_table__page=1&amp;page_table__sort_by=category_id&amp;page_table__sort_order=asc&amp;page_table_customer_phone__filter_value=403&amp;page_table_customer_active__filter_value=false' );
			$this->assertResponse( 'gridview2/?page_table__page=1&amp;page_table__sort_by=customer_name&amp;page_table__sort_order=asc&amp;page_table_customer_phone__filter_value=403&amp;page_table_customer_active__filter_value=false' );
			$this->assertResponse( 'page_table__page=1&amp;page_table__sort_by=customer_phone&amp;page_table__sort_order=desc&amp;page_table_customer_phone__filter_value=403&amp;page_table_customer_active__filter_value=false&amp;path=gridview2&amp;page_table_customer_name__filter_value=\'+encodeURIComponent(this.value)' );
			$this->assertResponse( 'page_table__page=1&amp;page_table__sort_by=customer_phone&amp;page_table__sort_order=desc&amp;page_table_customer_phone__filter_value=403&amp;page_table_customer_active__filter_value=false&amp;path=gridview2&amp;page_table_customer_phone__filter_value=\'+encodeURIComponent(this.value)' );
		}

		function testAjaxURL() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'path' => 'gridview2', 'customer_id' => '4', 'customer_name' => 'XXX', 'page_table_customer_name__filter_value' => 'g', 'async' => '1' ));

			$ds = \MyApp\Models\Customer::all();
			$this->assertEqual($ds->rows[3]['customer_id'], 4);
			$this->assertEqual($ds->rows[3]['category_id'], 1);
			$this->assertEqual($ds->rows[3]['customer_name'], 'XXX');

			$this->post( array( 'path' => 'gridview2', 'customer_id' => '4', 'category_id' => '2', 'page_table_customer_name__filter_value' => 'g', 'async' => '1' ));

			$ds = \MyApp\Models\Customer::all();
			$this->assertEqual($ds->rows[3]['customer_id'], 4);
			$this->assertEqual($ds->rows[3]['category_id'], 2);
			$this->assertEqual($ds->rows[3]['customer_name'], 'XXX');

			$this->assertMessage( 'Customer 4 has been updated' );
		}
	}
?>