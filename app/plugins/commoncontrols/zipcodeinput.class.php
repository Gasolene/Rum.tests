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
	class ZipCodeInput extends TextBox
	{
		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength				= 5;


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
			if( isset( $httpRequest[$this->getHTMLControlIdString()] ))
			{
				// remove dashes and make uppercase
				$httpRequest[$this->getHTMLControlIdString()] = strtoupper( str_replace( '-', '', $httpRequest[$this->getHTMLControlIdString()] ));
			}

			parent::onRequest($httpRequest);
		}


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->addValidator(new \System\Validators\PatternValidator('^[0-9][0-9][0-9][0-9][0-9]$^', $this->label . ' ' . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_zip_code', 'must be a valid zip code NNNNN')));
		}
	}
?>