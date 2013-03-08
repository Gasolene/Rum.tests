<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\InputBase;

	/**
     * handles button element creation and event handling
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		copyright (c) 2008
	 * @version			1.1.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class CardExpirationDateSelector extends InputBase
	{
		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field == 'month' ) {
				return (string) date( 'm', strtotime( $this->value ));
			}
			elseif( $field == 'year' ) {
				return (string) date( 'y', strtotime( $this->value ));
			}
			elseif( $field == 'value' ) {
				return (string) date( 'm/d/y', strtotime( $this->value ));
			}
			else return parent::__get( $field );
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
			/* format exp date based on request data */
			if( isset( $httpRequest[ $this->getHTMLControlId() . '__month' ] ) &&
				isset( $httpRequest[ $this->getHTMLControlId() . '__year' ] ))
			{
				$httpRequest[$this->getHTMLControlId()] = date( 'm/d/y', strtotime( $httpRequest[ $this->getHTMLControlId() . '__year' ] . '-' . $httpRequest[ $this->getHTMLControlId() . '__month' ] . '-01' ));

				unset( $httpRequest[ $this->getHTMLControlId() . '__month' ] );
				unset( $httpRequest[ $this->getHTMLControlId() . '__year' ] );
			}

			parent::onRequest($httpRequest);
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access public
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->defaultHTMLControlId = $this->getHTMLControlId().'__month';
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
			// create widgets
			$editRegion = $this->createDomObject( 'span' );
			$editRegion->appendAttribute( 'class', ' ccexpirydateinput' );

			$select_month = new \System\XML\DomObject( 'select' );
			$select_year = new \System\XML\DomObject( 'select' );

			$select_month->setAttribute( 'class', 'ccexpirydateinput_month');
			$select_year->setAttribute( 'class', 'ccexpirydateinput_year');

			$select_month->setAttribute( 'name', $this->getHTMLControlId() . '__month' );
			$select_year->setAttribute( 'name', $this->getHTMLControlId() . '__year' );

			$select_month->setAttribute( 'id', $this->getHTMLControlId() . '__month' );
			$select_year->setAttribute( 'id', $this->getHTMLControlId() . '__year' );

			$select_month->setAttribute( 'tabIndex', $this->tabIndex++ );
			$select_year->setAttribute( 'tabIndex', $this->tabIndex );

			// set onchange attribute
			if( $this->autoPostBack )
			{
				$select_month->setAttribute( 'onchange', 'submit();' );
				$select_year->setAttribute( 'onchange', 'submit();' );
			}

			if( $this->ajaxPostBack )
			{
				$select_month->appendAttribute( 'onchange', 'Rum.evalAsync(\'' . $this->ajaxCallback . '\',\'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\',\'POST\');' );
				$select_year->appendAttribute( 'onchange', 'Rum.evalAsync(\'' . $this->ajaxCallback . '\',\'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\',\'POST\');' );
			}

			// set invalid class
			if( $this->submitted && !$this->validate() ) {
				$select_month->setAttribute( 'class', 'invalid' );
				$select_year->setAttribute( 'class', 'invalid' );
			}

			// set readonly attribute
			if( $this->readonly )
			{
				$select_month->setAttribute( 'disabled', 'disabled' );
				$select_year->setAttribute( 'disabled', 'disabled' );
			}

			// set readonly attribute
			if( $this->disabled )
			{
				$select_month->setAttribute( 'disabled', 'disabled' );
				$select_year->setAttribute( 'disabled', 'disabled' );
			}

			// set visibility attribute
			if( !$this->visible )
			{
				$editRegion->setAttribute( 'style', 'display: none;' );
			}

			// select initial items
			$timestamp = strtotime( $this->value );
			if( !$timestamp ) {
				$timestamp = time();
			}

			$month = (int) date( 'm', $timestamp );
			$year = (int) date( 'Y', $timestamp );

			// create month element
			for( $i=1; $i <= 12; $i++ )
			{
				$option = new \System\XML\DomObject( 'option' );
				$option->setAttribute( 'value', $i );
				$option->nodeValue = date( 'm', strtotime( "$i/01/01" ));

				if( $i == $month ) {
					$option->setAttribute( 'selected', 'selected' );
				}

				$select_month->addChild( $option );
				unset( $option );
			}

			$yearMin  = (int) date( 'Y', time() );
			$yearMax  = (int) date( 'Y', time() ) + 6;

			// create year element
			for( $i = $yearMin; $i <= $yearMax; $i++ )
			{
				$option = new \System\XML\DomObject( 'option' );
				$option->setAttribute( 'value', $i );
				$option->nodeValue = date( 'y', strtotime( "01/01/$i" ));

				if( $i == $year ) {
					$option->setAttribute( 'selected', 'selected' );
				}

				$select_year->addChild( $option );
				unset( $option );
			}

			$slash = new \System\XML\DomObject( 'span' );
			$slash->nodeValue = '/';

			$editRegion->addChild( $select_month );
			$editRegion->addChild( $slash );
			$editRegion->addChild( $select_year );

			return $editRegion;
		}
	}
?>