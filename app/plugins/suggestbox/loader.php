<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace SuggestBox;

	// register event handler
	\System\Base\ApplicationBase::getInstance()->events->registerEventHandler(new \System\Base\Events\ApplicationRunEventHandler('\\SuggestBox\\registerFieldAndRuleTypes'));


	/**
	 * register field types and rules
	 * 
	 * @return void
	 */
	function registerFieldAndRuleTypes()
	{
		\System\Web\FormModelBase::registerFieldType('suggestbox', '\\SuggestBox\\SuggestBox');
		\System\Web\FormModelBase::registerFieldType('lookup', '\\SuggestBox\\Lookup');
	}
?>