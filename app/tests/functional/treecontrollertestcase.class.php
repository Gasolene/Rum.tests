<?php
    namespace MyApp\Controllers;

	class TreeControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testOnLoad() {
			$this->get();

			$this->assertResponse( '<li id="page_tree__node_root_1" class="expandable">' );
			$this->assertResponse( '<li id="page_tree__node_root_1_1">' );
			$this->assertResponse( '<li id="page_tree__node_root_1_2">' );
			$this->assertResponse( '<li id="page_tree__node_root_1_3">' );
			$this->assertResponse( '<li id="page_tree__node_root_2" class="expandable">' );
			$this->assertResponse( '<li id="page_tree__node_root_2_1" class="expandable">' );
			$this->assertResponse( '<li id="page_tree__node_root_2_1_1">' );
			$this->assertResponse( '<li id="page_tree__node_root_2_2" class="expandable">' );
			$this->assertResponse( '<li id="page_tree__node_root_2_3">' );
			$this->assertResponse( '<li id="page_tree__node_root_2_3_1">' );
			$this->assertResponse( '<li id="page_tree__node_root_3">' );
		}

		function testNodes() {
			$this->get();

			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_1'" );
			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2'" );
			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_1'" );
			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_2'" );
			$this->assertResponse( "<a class=\"fcollapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_3'" );

			$this->get( array( 'page_tree__root_2_expand' => '1', 'page' => 'tree' ));

			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_1'" );
			$this->assertResponse( "<a class=\"expanded\" title=\"expanded\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2'" );
			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_1'" );
			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_2'" );
			$this->assertResponse( "<a class=\"fcollapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_3'" );

			$this->post( array( 'page_tree__root_2_2_expand' => '1', 'page_tree__submitted' => '1', 'page' => 'tree' ));
			$this->get();

			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_1'" );
			$this->assertResponse( "<a class=\"expanded\" title=\"expanded\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2'" );
			$this->assertResponse( "<a class=\"collapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_1'" );
			$this->assertResponse( "<a class=\"expanded\" title=\"expanded\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_2'" );
			$this->assertResponse( "<a class=\"fcollapsed\" title=\"collapsed\" onclick=\"PHPRum.treeviewToggleNode('page_tree','root_2_3'" );
		}
	}
?>