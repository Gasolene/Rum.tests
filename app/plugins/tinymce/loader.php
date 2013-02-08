<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace TinyMCE;

	// register event handler
	\System\Base\ApplicationBase::getInstance()->events->registerEventHandler(new \System\Base\Events\ApplicationRunEventHandler('\\TinyMCE\\registerFieldAndRuleTypes'));


	/**
	 * register field types and rules
	 * 
	 * @return void
	 */
	function registerFieldAndRuleTypes()
	{
		\System\Web\FormModelBase::registerFieldType('blob', '\\TinyMCE\\TinyMCE');
	}
?>