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
		 * process the HTTP request array
		 *
		 * @param  array		&$request	request data
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( isset( $request[$this->getHTMLControlId()] ))
			{
				// format
				$request[$this->getHTMLControlId()] = date("Y-m-d H:i:s",  strtotime($request[$this->getHTMLControlId()]));
			}

			parent::onRequest($request);
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

			if(strtotime($this->value)!==false)
			{
				$input->setAttribute( 'value', date("m/d/Y",  strtotime($this->value)) );
			}

			return $input;
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("Rum.id('{$this->getHTMLControlId()}').value='".date("m/d/Y",  strtotime($this->value))."';");
		}
	}
?>