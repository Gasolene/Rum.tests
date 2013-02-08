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
	 * @copyright		copyright (c) 2008
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class CardSecurityCodeInput extends TextBox {

		/**
		 * Specifies the size of a textbox
		 * @access protected
		 */
		protected $size						= 4;


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			// Install assets
			if(!file_exists(__HTDOCS_PATH__ . '/assets/commoncontrols/cardcvv2input'))
			{
				\System\Utils\FileSystem::copy(__PLUGINS_PATH__ . str_replace(__PLUGINS_PATH__, '', str_replace('\\', '/', __DIR__)) . '/assets/cardcvv2input', __HTDOCS_PATH__ . '/assets/commoncontrols/cardcvv2input');
			}

			// include external resources
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Base\ApplicationBase::getInstance()->config->assets . '/commoncontrols/cardcvv2input/cardcvv2input.css' );

			$this->addValidator(new CardSecurityCodeValidator());
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
			$input = parent::getDomObject();
			$input->appendAttribute( 'class', ' cardcvv2input' );

			$a = new \System\XML\DomObject( 'a' );
			$a->setAttribute( 'class', 'cardcvv2input_help' );
			$a->setAttribute( 'href', "javascript:void window.open( '".__PROTOCOL__."://" . \System\Base\ApplicationBase::getInstance()->config->host . \System\Base\ApplicationBase::getInstance()->config->assets . '/commoncontrols/cardcvv2input/cvv2.php?base='.htmlentities(\System\Base\ApplicationBase::getInstance()->config->assets).'' . "', '', 'height=260,width=520,top='+((screen.height/2)-(130))+',left='+((screen.width/2)-(260))+',scrollbars=no' );" );
			$a->nodeValue = "(what's this)";

			$span = new \System\XML\DomObject( 'span' );
			$span->addChild( $input );
			$span->addChild( $a );

			return $span;
		}
	}
?>