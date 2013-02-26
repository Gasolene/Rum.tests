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
		$page->addLink(\System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'commoncontrols', 'type'=>'text/css')).'&asset=css/ui-lightness/jquery-ui-1.8.19.custom.css');
		$page->addScript(\System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'commoncontrols', 'type'=>'text/javascript')).'&asset=js/jquery-1.7.2.min.js');
		$page->addScript(\System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'commoncontrols', 'type'=>'text/javascript')).'&asset=js/jquery-ui-1.8.19.custom.min.js');
	}
?>