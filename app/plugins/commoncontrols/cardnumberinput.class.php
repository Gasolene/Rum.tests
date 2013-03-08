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
	 * @version			1.1.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class CardNumberInput extends TextBox {

		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength				= 19;

		/**
		 * Specifies the size of a textbox
		 * @access protected
		 */
		protected $size						= 20;


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			$this->addValidator(new CardNumberValidator());
		}


		/**
		 * card types
		 * @var array
		 * /
		protected $cardTypes = array(
			array('MASTERCARD', array('51', '52', '53', '54', '55'), 16, 16, true),
			array('VISA', array('4'), 13, 16, true),
			array('AMEX', array('34', '37'), 15, 15, true),
			array('DinersClub', array('300', '301', '302', '303', '304', '305', '36', '38'), 14, 14, true),
			array('Discover', array('6011'), 16, 16, true),
			array('enRoute', array('2014', '2149'), 15, 15, false),
			array('JCB', array('3088', '3096', '3112', '3158', '3337', '3528', '2131', '1800'), 15, 16, true)
		);


		/**
		 * process the HTTP request array
		 *
		 * @param  object	$httpRequest	HTTPRequest Object
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest )
		{
			/* format postal/zip code based on request data */
			if( isset( $httpRequest[$this->getHTMLControlId()] ))
			{
				// remove dashes and spaces
				$httpRequest[$this->getHTMLControlId()] = trim( str_replace( '-', '', str_replace( ' ', '', $httpRequest[$this->getHTMLControlId()] )));
				//$httpRequest[$this->getHTMLControlId()] = preg_replace('/\D/', '', $httpRequest[$this->getHTMLControlId()]);
			}

			parent::onRequest($httpRequest);
		}
	}
?>