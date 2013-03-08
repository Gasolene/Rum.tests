<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace DatePicker;
	use \System\Web\WebControls\TextBox;


	/**
     * handles text control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		DatePicker
	 */
	class DatePicker extends TextBox
	{
		/**
		 * Specifies whether to show image(s) or not (optional)
		 * @access public
		 */
		protected $showImage				= true;

		/**
		 * Specifies whether to enabled the browsers auto complete feature
		 * @access public
		 */
		protected $disableAutoComplete		= true;


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->addValidator(new \System\Validators\DateTimeValidator());
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'datepicker', 'type'=>'text/css')) . '&asset=datepicker.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'datepicker', 'type'=>'text/javascript')) . '&asset=datepicker.js' );
		}


		/**
		 * returns widget object
		 *
		 * @param  none
		 * @return void
		 * @access public
		 */
		public function getDomObject()
		{
			$text = parent::getDomObject();
			$text->appendAttribute( 'onclick', 'drawCalendar(\'' . $this->getHTMLControlId() . '\');' );
			$text->appendAttribute( 'class', ' datepicker' );
			//$text->appendAttribute( 'onchange', 'alert(\'s\');' );

			if( $this->value === '0000-00-00' ) {
				$text->setAttribute( 'value', '' );
			}

			$span = new \System\XML\DomObject( 'span' );
			$span->setAttribute( 'id', $this->getHTMLControlId() . '__cal' );
			$span->setAttribute( 'class', 'datepicker_cal' );

			$img = new \System\XML\DomObject( 'img' );
			$img->setAttribute( 'src',      \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'datepicker', 'type'=>'image/gif')) . '&asset=icon.gif' );
			$img->setAttribute( 'alt',     'datepicker' );
			$img->setAttribute( 'class',   'datepicker_img' );
			$img->setAttribute( 'onclick', 'drawCalendar(\'' . $this->getHTMLControlId() . '\')' );

			$datepicker = new \System\XML\DomObject( 'span' );
			$datepicker->setAttribute( 'style', 'position: relative;' );
			$datepicker->addChild( $text );
			$datepicker->addChild( $span );
			if( $this->showImage ) $datepicker->addChild( $img );

			return $datepicker;
		}
	}
?>