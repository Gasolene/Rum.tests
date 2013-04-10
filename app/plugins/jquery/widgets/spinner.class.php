<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace JQuery;


	/**
     * Represents a JQuery Spinner input control
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			JQuery
	 * @subpackage		Web
	 */
	class Spinner extends \System\Web\WebControls\InputBase
	{
		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->getParentByType('\System\Web\WebControls\Page')->onload .= "$( \"#{$this->getHTMLControlId()}\" ).datepicker();";
		}


		/**
		 * returns widget object
		 *
		 * @param  none
		 * @return void
		 * @access public
		 */
		public function getDomObject()
		{
			$input = parent::getDomObject();

			if(!is_null($this->value))
			{
				$input->setAttribute( 'value', $this->value );
			}

			return $input;
		}
	}
?>