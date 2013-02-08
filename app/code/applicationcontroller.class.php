<?php

    namespace MyApp;

	class ApplicationController extends \System\Controllers\PageControllerBase
	{
		public function onPageCreate( &$page, $args )
		{
            $master = UI::MasterView('master');
            $master->template = \Rum::config()->views . '/_includes/common.tpl';
			$master->assign('title', 'test');
            $page->setMaster($master);
		}
	}
?>