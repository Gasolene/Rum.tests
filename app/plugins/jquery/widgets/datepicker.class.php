<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace JQuery;


	/**
	 * handles text control element creation
	 * abstracts away the presentation logic and data access layer
	 * the server-side control for WebWidgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			JQuery
	 * @subpackage		Web
	 */
	class DatePicker extends JQueryWidgetBase
	{
		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->getParentByType('\System\Web\WebControls\Page')->onload .= "$( \"#{$this->getHTMLControlId()}\" ).datepicker({$this->getOptions()});";
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