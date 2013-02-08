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
	class MoneyInput extends TextBox
	{
		/**
		 * Specifies the size of a textbox
		 * @access public
		 */
		protected $size							= 8;


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->addValidator(new \System\Validators\NumericValidator());
		}


		/**
		 * process the HTTP request array
		 *
		 * @param  object	$httpRequest	HTTPRequest Object
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest ) {

			/* format phone number based on request data */
			if( isset( $httpRequest[$this->getHTMLControlIdString()] )) {

				$httpRequest[$this->getHTMLControlIdString()] = (string) $httpRequest[$this->getHTMLControlIdString()];

				// remove dollor signs
				$httpRequest[$this->getHTMLControlIdString()] = str_replace( '$', '', $httpRequest[$this->getHTMLControlIdString()] );

				// if brackets then negative
				$negative = false;
				if( strpos( $httpRequest[$this->getHTMLControlIdString()], '(' ) !== false &&
					strpos( $httpRequest[$this->getHTMLControlIdString()], ')' )) {
					$negative = true;
				}

				// remove brackets
				$httpRequest[$this->getHTMLControlIdString()] = str_replace( '(', '', str_replace( ')', '', $httpRequest[$this->getHTMLControlIdString()] ) );

				// remove spaces
				$httpRequest[$this->getHTMLControlIdString()] = str_replace( ' ', '', $httpRequest[$this->getHTMLControlIdString()] );

				if( is_numeric( $httpRequest[$this->getHTMLControlIdString()] )) {

					// strip trailing zeros
					$httpRequest[$this->getHTMLControlIdString()] = number_format( $httpRequest[$this->getHTMLControlIdString()], 2, '.', '' );

					// cast
					$httpRequest[$this->getHTMLControlIdString()] = (real) $httpRequest[$this->getHTMLControlIdString()];
					if( $negative ) {
						$httpRequest[$this->getHTMLControlIdString()] = -$httpRequest[$this->getHTMLControlIdString()];
					}
				}
			}

			parent::onRequest($httpRequest);
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
			$this->multiline = false;
			$dom = parent::getDomObject();
			$dom->appendAttribute( 'class', ' priceinput' );

			if( $this->value < 0 ) {
				$dom->setAttribute( 'style', 'text-align:right;color:#FF0000;' );
				$dom->setAttribute( 'value', '($' . str_replace( '-', '', number_format( $this->value, 2, '.', ' ' )) . ')' );
			}
			else {
				$dom->setAttribute( 'style', 'text-align:right;' );

				if( $dom->name === 'input' ) {
					$dom->setAttribute( 'value', '$' . number_format( (real)$this->value, 2, '.', ' ' ) . ' ' );
				}
				else {
					$dom->input->setAttribute( 'value', '$' . number_format( $this->value, 2, '.', ' ' ) . '' );
					$dom->input->appendAttribute( 'style', 'text-align:right;' );
				}
			}

			return $dom;
		}
	}
?>