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
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class PostalCodeInput extends TextBox
	{
		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength				= 7;

		/**
		 * Specifies the size of a textbox, default is 30
		 * @var int
		 */
		protected $size						= 7;


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->addValidator(new \System\Validators\PatternValidator('^[A-Za-z][0-9][A-Za-z]( )?[0-9][A-Za-z][0-9]$^', $this->label . ' ' . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_postal_code', 'must be a valid postal code')));
		}



		/**
		 * process the HTTP request array
		 *
		 * @param  object	$httpRequest	HTTPRequest Object
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest ) {

			/* format postal/zip code based on request data */
			if( isset( $httpRequest[$this->getHTMLControlIdString()] )) {

				// remove dashes and make uppercase
				$httpRequest[$this->getHTMLControlIdString()] = strtoupper( str_replace( array('-', ' '), '', $httpRequest[$this->getHTMLControlIdString()] ));

				// add space if 6 characters
				if( strlen( $httpRequest[$this->getHTMLControlIdString()] ) == 6 ) {
					$httpRequest[$this->getHTMLControlIdString()] = $httpRequest[$this->getHTMLControlIdString()][0]
						. $httpRequest[$this->getHTMLControlIdString()][1]
						. $httpRequest[$this->getHTMLControlIdString()][2]
						. ' '
						. $httpRequest[$this->getHTMLControlIdString()][3]
						. $httpRequest[$this->getHTMLControlIdString()][4]
						. $httpRequest[$this->getHTMLControlIdString()][5];
				}
			}

			parent::onRequest($httpRequest);
		}
	}
?>