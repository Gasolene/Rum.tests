<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JScroll;

	// register event handler
	\System\Base\ApplicationBase::getInstance()->events->registerEventHandler(new \System\Web\Events\WebApplicationHandleRequestEventHandler('\\JScroll\\setCreatePageEventHandler'));

	/**
	 * on request handler event
	 * 
	 * @return void
	 */
	function setCreatePageEventHandler()
	{
		\System\Base\ApplicationBase::getInstance()->requestHandler->events->registerEventHandler(new \System\Web\Events\PageControllerCreatePageEventHandler('\\JScroll\\loadModules'));
	}

	/**
	 * on create page event
	 * 
	 * @return void
	 */
	function loadModules()
	{
		$page = \System\Base\ApplicationBase::getInstance()->requestHandler->page;
		$page->addScript(\System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'jscroll', 'type'=>'text/javascript')).'&asset=jquery.jscroll.js');
		$page->addScript(\System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'jscroll', 'type'=>'text/javascript')).'&asset=jquery.jscroll.min.js');
	}
?>