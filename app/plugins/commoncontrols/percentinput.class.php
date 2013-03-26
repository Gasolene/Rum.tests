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
	class PercentInput extends TextBox
	{
		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength					= 8;

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
		protected function onRequest( array &$httpRequest )
		{
			/* format postal/zip code based on request data */
			if( isset( $httpRequest[$this->getHTMLControlId()] ))
			{
				// remove dashes and make uppercase
				$httpRequest[$this->getHTMLControlId()] = (float) str_replace( '%', '', $httpRequest[$this->getHTMLControlId()] );
				$httpRequest[$this->getHTMLControlId()] /= 100;
				$httpRequest[$this->getHTMLControlId()] = $httpRequest[$this->getHTMLControlId()]<0?0:(float)$httpRequest[$this->getHTMLControlId()];
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
			$value = (float) number_format(( $this->value *= 100 ), 2 ) . '%';

			$dom = parent::getDomObject();
			$dom->appendAttribute( 'style', 'text-align:right;' );
			$dom->appendAttribute( 'class', ' percentinput' );

			if( $dom->name === 'input' ) {
				$dom->setAttribute( 'value', $value );
			}
			else {
				$dom->input->setAttribute( 'value', $value );
				$dom->input->appendAttribute( 'style', 'text-align:right;' );
			}

			return $dom;
		}
	}
?>