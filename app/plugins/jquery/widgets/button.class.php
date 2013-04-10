<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace JQuery;


	/**
     * Represents a JQuery Button Widget
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			JQuery
	 * @subpackage		Web
	 */
	class Button extends \System\Web\WebControls\InputBase
	{
		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->getParentByType('\System\Web\WebControls\Page')->onload .= "$( \"#{$this->getHTMLControlId()}\" ).button().click(function( event ) {return Rum.submit(Rum.id('{$form->getHTMLControlId()}'), ' . ( 'Rum.evalFormResponse);});";
		}
	}
?>