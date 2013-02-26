<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace JQuery;

	// register event handler
	\System\Base\ApplicationBase::getInstance()->events->registerEventHandler(new \System\Web\Events\WebApplicationHandleRequestEventHandler('\\JQuery\\onRequestHandler'));

	/**
	 * on request handler event
	 * 
	 * @return void
	 */
	function onRequestHandler()
	{
		\System\Base\ApplicationBase::getInstance()->requestHandler->events->registerEventHandler(new \System\Web\Events\PageControllerCreatePageEventHandler('\\JQuery\\onCreatePage'));
	}

	/**
	 * on create page event
	 * 
	 * @return void
	 */
	function onCreatePage()
	{
		$page = \System\Base\ApplicationBase::getInstance()->requestHandler->page;
		$page->addLink(__ASSETS_URI__.'/jquery/css/ui-lightness/jquery-ui-1.8.19.custom.css');
		$page->addScript(__ASSETS_URI__.'/jquery/js/jquery-1.7.2.min.js');
		$page->addScript(__ASSETS_URI__.'/jquery/js/jquery-ui-1.8.19.custom.min.js');
	}
?>