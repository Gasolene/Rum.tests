<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\TextBox;

	/**
     * handles button element creation and event handling
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		copyright (c) 2008
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class CardSecurityCodeInput extends TextBox {

		/**
		 * Specifies the size of a textbox
		 * @access protected
		 */
		protected $size						= 4;


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'cardcvv2input', 'type'=>'text/css')) . '&asset=cardcvv2input/cardcvv2input.css' );

			$this->addValidator(new CardSecurityCodeValidator());
		}


		/**
		 * returns widget object
		 *
		 * @param  none
		 * @return void
		 * @access protected
		 */
		public function getDomObject()
		{
			$input = parent::getDomObject();
			$input->appendAttribute( 'class', ' cardcvv2input' );

			$a = new \System\XML\DomObject( 'a' );
			$a->setAttribute( 'class', 'cardcvv2input_help' );
			$a->setAttribute( 'href', "javascript:void window.open( '".__PROTOCOL__."://" . \System\Base\ApplicationBase::getInstance()->config->host . \System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'commoncontrols', 'type'=>'text/html')) . '&asset=cardcvv2input/cvv2.html' . "', '', 'height=260,width=520,top='+((screen.height/2)-(130))+',left='+((screen.width/2)-(260))+',scrollbars=no' );" );
			$a->nodeValue = "(what's this)";

			$span = new \System\XML\DomObject( 'span' );
			$span->addChild( $input );
			$span->addChild( $a );

			return $span;
		}
	}
?>