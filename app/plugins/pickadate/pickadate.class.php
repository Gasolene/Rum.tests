<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace PickADate;
	use \System\Web\WebControls\TextBox;


	/**
     * handles text control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		DatePicker
	 */
	class PickADate extends TextBox
	{
		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->addValidator(new \System\Validators\DateTimeValidator());
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'pickadate', 'type'=>'text/css')) . '&asset=pickadate.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'pickadate', 'type'=>'text/css')) . '&asset=custom.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'pickadate', 'type'=>'text/javascript')) . '&asset=pickadate.js' );
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
			$span = new \System\XML\DomObject('span');
			$input = parent::getDomObject();
			$input->appendAttribute( 'class', ' datepicker' );

			$script = new \System\XML\DomObject('span');
			
			//Ensure value is formatting such that PickADate.js can use the value
			$formattedDate = date('Y-n-j', strtotime($this->value));
			$arrayFormatValue = "['".str_replace('-', "', '", $formattedDate)."']";
			
			$script->innerHtml = "
			<script type=\"text/javascript\">
				$( '#".$this->getHTMLControlIdString()."' ).pickadate({
					date_min:		false,
					date_max:		false,
					date_selected:	".$arrayFormatValue.",
					format:			'yyyy-mm-dd',
					format_submit:	'yyyy-mm-dd'
				});
			</script>";

			$span->addChild($input);
			$span->addChild($script);
			return $span;
		}
	}
	?>