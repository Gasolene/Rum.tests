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
	class URLInput extends TextBox
	{
		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			$this->addValidator(new \System\Validators\URLValidator());
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

				if( strpos( $httpRequest[$this->getHTMLControlId()], 'http' ) === false && strlen($httpRequest[$this->getHTMLControlId()]) > 0) {
					$httpRequest[$this->getHTMLControlId()] = 'http://' . $httpRequest[$this->getHTMLControlId()];
				}

				$httpRequest[$this->getHTMLControlId()] = trim( $httpRequest[$this->getHTMLControlId()] );
			}

			parent::onRequest($httpRequest);
		}
	}
?>