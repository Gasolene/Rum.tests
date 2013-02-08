<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace PickADate;

	// register event handler
	\System\Base\ApplicationBase::getInstance()->events->registerEventHandler(new \System\Base\Events\ApplicationRunEventHandler('\\PickADate\\registerFieldAndRuleTypes'));


	/**
	 * register field types and rules
	 * 
	 * @return void
	 */
	function registerFieldAndRuleTypes()
	{
		\System\Web\FormModelBase::registerFieldType('date', '\\PickADate\\PickADate');
	}
?>