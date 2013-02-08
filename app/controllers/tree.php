<?php
    namespace MyApp\Controllers;
    use \MyApp\App;

	class Tree extends \MyApp\ApplicationController {

		function onPageInit( &$page, $args ) {
			

			$this->page->add( new \System\UI\WebControls\TreeView( 'tree' ));
			$this->page->tree->rootNode = new \System\UI\WebControls\TreeNode( 'root', 'home' );
			$this->page->tree->rootNode->createChild( 'a' );
			$this->page->tree->rootNode->createChild( 'b' );
			$this->page->tree->rootNode->createChild( 'c' );
			$this->page->tree->rootNode->root_1->createChild( 'a1' );
			$this->page->tree->rootNode->root_1->createChild( 'a2' );
			$this->page->tree->rootNode->root_1->createChild( 'a3' );
			$this->page->tree->rootNode->root_2->createChild( 'b1' );
			$this->page->tree->rootNode->root_2->createChild( 'b2' );
			$this->page->tree->rootNode->root_2->createChild( 'b3' );
			$this->page->tree->rootNode->root_2->root_2_1->createChild( 'b1 i' );
			$this->page->tree->rootNode->root_2->root_2_2->createChild( 'b2 i' );
			$this->page->tree->rootNode->root_2->root_2_3->createChild( 'b3 i' );

			$this->page->tree->showRoot = true;
			$this->page->tree->showIcons = false;

			$this->page->tree->rootNode->root_2->root_2_1->imgSrc = \Rum::config()->assets . '/treeview/folder_closed.gif';
			$this->page->tree->rootNode->root_2->root_2_1->root_2_1_1->imgSrc = \Rum::config()->assets . '/treeview/folder_opened.gif';
			$this->page->tree->rootNode->root_2->root_2_2->textOrHtml = '<a href="http://google.ca">Goto Google</a>';
			$this->page->tree->rootNode->root_2->root_2_3->textOrHtml = '<a href="http://google.ca">Goto Google</a>';

			\Rum::trace('hello');
		}
	}
?>