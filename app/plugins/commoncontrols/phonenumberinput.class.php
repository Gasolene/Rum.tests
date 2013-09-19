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
	class PhoneNumberInput extends Text
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
			if( isset( $httpRequest[$this->getHTMLControlId()] ))
			{
				if( strlen( $httpRequest[$this->getHTMLControlId()] ) > 0 )
				{
					// remove dashes spaces and brackets
					$httpRequest[$this->getHTMLControlId()] = str_replace( '-', '', str_replace( ' ', '', str_replace( '(', '', str_replace( ')', '', $httpRequest[$this->getHTMLControlId()] ))));

					// replace ext with x
					$httpRequest[$this->getHTMLControlId()] = str_ireplace( 'ext', 'x', $httpRequest[$this->getHTMLControlId()] );

					// get extension pos
					$ext_pos = stripos( $httpRequest[$this->getHTMLControlId()], 'x' );

					// store extension
					$ext = "";
					if( $ext_pos )
					{
						$ext = substr( $httpRequest[$this->getHTMLControlId()], $ext_pos + 1 );
						$httpRequest[$this->getHTMLControlId()] = substr( $httpRequest[$this->getHTMLControlId()], 0, $ext_pos );
					}

					// 7 character phone no.
					if( strlen( $httpRequest[$this->getHTMLControlId()] ) == 7 )
					{
						$httpRequest[$this->getHTMLControlId()] = '(???) '
							. $httpRequest[$this->getHTMLControlId()][0]
							. $httpRequest[$this->getHTMLControlId()][1]
							. $httpRequest[$this->getHTMLControlId()][2]
							. '-'
							. $httpRequest[$this->getHTMLControlId()][3]
							. $httpRequest[$this->getHTMLControlId()][4]
							. $httpRequest[$this->getHTMLControlId()][5]
							. $httpRequest[$this->getHTMLControlId()][6];
					}
					// 10 character phone no.
					elseif( strlen( $httpRequest[$this->getHTMLControlId()] ) == 10 )
					{
						$httpRequest[$this->getHTMLControlId()] = '('
							. $httpRequest[$this->getHTMLControlId()][0]
							. $httpRequest[$this->getHTMLControlId()][1]
							. $httpRequest[$this->getHTMLControlId()][2]
							. ') '
							. $httpRequest[$this->getHTMLControlId()][3]
							. $httpRequest[$this->getHTMLControlId()][4]
							. $httpRequest[$this->getHTMLControlId()][5]
							. '-'
							. $httpRequest[$this->getHTMLControlId()][6]
							. $httpRequest[$this->getHTMLControlId()][7]
							. $httpRequest[$this->getHTMLControlId()][8]
							. $httpRequest[$this->getHTMLControlId()][9];
					}
					// 11 character phone no.
					elseif( strlen( $httpRequest[$this->getHTMLControlId()] ) == 11 )
					{
						$httpRequest[$this->getHTMLControlId()] = '('
							. $httpRequest[$this->getHTMLControlId()][1]
							. $httpRequest[$this->getHTMLControlId()][2]
							. $httpRequest[$this->getHTMLControlId()][3]
							. ') '
							. $httpRequest[$this->getHTMLControlId()][4]
							. $httpRequest[$this->getHTMLControlId()][5]
							. $httpRequest[$this->getHTMLControlId()][6]
							. '-'
							. $httpRequest[$this->getHTMLControlId()][7]
							. $httpRequest[$this->getHTMLControlId()][8]
							. $httpRequest[$this->getHTMLControlId()][9]
							. $httpRequest[$this->getHTMLControlId()][10];
					}

					// add extension
					if( $ext_pos )
					{
						$httpRequest[$this->getHTMLControlId()] .= ' ext '
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