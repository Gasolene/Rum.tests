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

			$this->getParentByType( '\System\Web\WebControls\Page' )
					->addLink( \System\Web\WebApplicationBase::getInstance()
					->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'jquery', 'type'=>'application/javascript')) . 
					'&asset=spinner/jquery-mousewheel-master/jquery.mousewheel.js' );
			$this->getParentByType('\System\Web\WebControls\Page')->onload .= "$( \"#{$this->getHTMLControlId()}\" ).spinner();";
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