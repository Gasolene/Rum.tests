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
	class PhoneNumberInput extends TextBox
	{
		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength				= 24;


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			$this->addValidator(new PhoneNumberValidator());
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
			/* format phone number based on request data */
			if( isset( $httpRequest[$this->getHTMLControlIdString()] ))
			{
				if( strlen( $httpRequest[$this->getHTMLControlIdString()] ) > 0 )
				{
					// remove dashes spaces and brackets
					$httpRequest[$this->getHTMLControlIdString()] = str_replace( '-', '', str_replace( ' ', '', str_replace( '(', '', str_replace( ')', '', $httpRequest[$this->getHTMLControlIdString()] ))));

					// replace ext with x
					$httpRequest[$this->getHTMLControlIdString()] = str_ireplace( 'ext', 'x', $httpRequest[$this->getHTMLControlIdString()] );

					// get extension pos
					$ext_pos = stripos( $httpRequest[$this->getHTMLControlIdString()], 'x' );

					// store extension
					$ext = "";
					if( $ext_pos )
					{
						$ext = substr( $httpRequest[$this->getHTMLControlIdString()], $ext_pos + 1 );
						$httpRequest[$this->getHTMLControlIdString()] = substr( $httpRequest[$this->getHTMLControlIdString()], 0, $ext_pos );
					}

					// 7 character phone no.
					if( strlen( $httpRequest[$this->getHTMLControlIdString()] ) == 7 )
					{
						$httpRequest[$this->getHTMLControlIdString()] = '(???) '
							. $httpRequest[$this->getHTMLControlIdString()][0]
							. $httpRequest[$this->getHTMLControlIdString()][1]
							. $httpRequest[$this->getHTMLControlIdString()][2]
							. '-'
							. $httpRequest[$this->getHTMLControlIdString()][3]
							. $httpRequest[$this->getHTMLControlIdString()][4]
							. $httpRequest[$this->getHTMLControlIdString()][5]
							. $httpRequest[$this->getHTMLControlIdString()][6];
					}
					// 10 character phone no.
					elseif( strlen( $httpRequest[$this->getHTMLControlIdString()] ) == 10 )
					{
						$httpRequest[$this->getHTMLControlIdString()] = '('
							. $httpRequest[$this->getHTMLControlIdString()][0]
							. $httpRequest[$this->getHTMLControlIdString()][1]
							. $httpRequest[$this->getHTMLControlIdString()][2]
							. ') '
							. $httpRequest[$this->getHTMLControlIdString()][3]
							. $httpRequest[$this->getHTMLControlIdString()][4]
							. $httpRequest[$this->getHTMLControlIdString()][5]
							. '-'
							. $httpRequest[$this->getHTMLControlIdString()][6]
							. $httpRequest[$this->getHTMLControlIdString()][7]
							. $httpRequest[$this->getHTMLControlIdString()][8]
							. $httpRequest[$this->getHTMLControlIdString()][9];
					}
					// 11 character phone no.
					elseif( strlen( $httpRequest[$this->getHTMLControlIdString()] ) == 11 )
					{
						$httpRequest[$this->getHTMLControlIdString()] = '('
							. $httpRequest[$this->getHTMLControlIdString()][1]
							. $httpRequest[$this->getHTMLControlIdString()][2]
							. $httpRequest[$this->getHTMLControlIdString()][3]
							. ') '
							. $httpRequest[$this->getHTMLControlIdString()][4]
							. $httpRequest[$this->getHTMLControlIdString()][5]
							. $httpRequest[$this->getHTMLControlIdString()][6]
							. '-'
							. $httpRequest[$this->getHTMLControlIdString()][7]
							. $httpRequest[$this->getHTMLControlIdString()][8]
							. $httpRequest[$this->getHTMLControlIdString()][9]
							. $httpRequest[$this->getHTMLControlIdString()][10];
					}

					// add extension
					if( $ext_pos )
					{
						$httpRequest[$this->getHTMLControlIdString()] .= ' ext '
							. (isset($ext[0])?(int)$ext[0]:'')
							. (isset($ext[1])?(int)$ext[1]:'')
							. (isset($ext[2])?(int)$ext[2]:'')
							. (isset($ext[3])?(int)$ext[3]:'')
							. (isset($ext[4])?(int)$ext[4]:'');
					}
				}
			}

			parent::onRequest($httpRequest);
		}
	}
?>