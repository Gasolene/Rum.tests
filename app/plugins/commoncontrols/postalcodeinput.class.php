<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\Text;


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
	class PostalCodeInput extends Text
	{
		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength				= 7;

		/**
		 * Specifies the size of a Text, default is 30
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
			if( isset( $httpRequest[$this->getHTMLControlId()] )) {

				// remove dashes and make uppercase
				$httpRequest[$this->getHTMLControlId()] = strtoupper( str_replace( array('-', ' '), '', $httpRequest[$this->getHTMLControlId()] ));

				// add space if 6 characters
				if( strlen( $httpRequest[$this->getHTMLControlId()] ) == 6 ) {
					$httpRequest[$this->getHTMLControlId()] = $httpRequest[$this->getHTMLControlId()][0]
						. $httpRequest[$this->getHTMLControlId()][1]
						. $httpRequest[$this->getHTMLControlId()][2]
						. ' '
						. $httpRequest[$this->getHTMLControlId()][3]
						. $httpRequest[$this->getHTMLControlId()][4]
						. $httpRequest[$this->getHTMLControlId()][5];
				}
			}

			parent::onRequest($httpRequest);
		}
	}
?>