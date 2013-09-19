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
	class MoneyInput extends Text
	{
		/**
		 * Specifies the size of a Text
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
			if( isset( $httpRequest[$this->getHTMLControlId()] )) {

				$httpRequest[$this->getHTMLControlId()] = (string) $httpRequest[$this->getHTMLControlId()];

				// remove dollor signs
				$httpRequest[$this->getHTMLControlId()] = str_replace( '$', '', $httpRequest[$this->getHTMLControlId()] );

				// if brackets then negative
				$negative = false;
				if( strpos( $httpRequest[$this->getHTMLControlId()], '(' ) !== false &&
					strpos( $httpRequest[$this->getHTMLControlId()], ')' )) {
					$negative = true;
				}

				// remove brackets
				$httpRequest[$this->getHTMLControlId()] = str_replace( '(', '', str_replace( ')', '', $httpRequest[$this->getHTMLControlId()] ) );

				// remove spaces
				$httpRequest[$this->getHTMLControlId()] = str_replace( ' ', '', $httpRequest[$this->getHTMLControlId()] );

				if( is_numeric( $httpRequest[$this->getHTMLControlId()] )) {

					// strip trailing zeros
					$httpRequest[$this->getHTMLControlId()] = number_format( $httpRequest[$this->getHTMLControlId()], 2, '.', '' );

					// cast
					$httpRequest[$this->getHTMLControlId()] = (real) $httpRequest[$this->getHTMLControlId()];
					if( $negative ) {
						$httpRequest[$this->getHTMLControlId()] = -$httpRequest[$this->getHTMLControlId()];
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