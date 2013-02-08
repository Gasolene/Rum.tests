<?php
	namespace MyApp\Controllers;
    use MyApp\App;

	class ViewSource extends \MyApp\ApplicationController
	{
		function onPageLoad( &$page, $args ) {
			return;
			if(\System\IO\HTTPRequest::$get['id'])
			{
				$this->page->setData( highlight_file( \Rum::config()->controllers . '/' . strtolower( \System\IO\HTTPRequest::$get['id'] . '.php' ), TRUE ));
			}
		}
	}
?>