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
	class YearInput extends TextBox
	{
		/**
		 * Specifies the size of a textbox
		 * @access public
		 */
		protected $size						= 4;

		/**
		 * Specifies that the value should be numeric
		 * @access public
		 */
		protected $maxLength				= 4;


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->addValidator(new \System\Validators\LengthValidator(4, 4));
		}


		/**
		 * The constructor sets attributes based on session data, triggering events, and is responcible for 
		 * formatting the proper request value and garbage handling
		 *
		 * @return void
		 * @access public
		 */
		public function YearInput( $controlId, $default = null ) {
			$default = $default?$default:date( 'Y', time() );
			parent::__construct( $controlId, $default );
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
			if( isset( $httpRequest[$this->getHTMLControlIdString()] ))
			{
				$httpRequest[$this->getHTMLControlIdString()] = (int) $httpRequest[$this->getHTMLControlIdString()];
			}

			parent::onRequest($httpRequest);
		}
	}
?>