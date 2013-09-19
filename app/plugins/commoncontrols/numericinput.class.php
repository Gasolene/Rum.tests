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
	class NumericInput extends Text
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

			$this->addValidator(new \System\Validators\NumericValidator());
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
			$dom->appendAttribute( 'class', ' numericinput' );

			if( $this->value < 0 ) {
				$dom->setAttribute( 'style', 'text-align:right;color:#FF0000;' );

				if( $dom->name != 'input' ) {
					$dom->input->appendAttribute( 'style', 'text-align:right;color:#FF0000;' );
				}
			}
			else {
				$dom->setAttribute( 'style', 'text-align:right;' );

				if( $dom->name != 'input' ) {
					$dom->input->appendAttribute( 'style', 'text-align:right;' );
				}
			}

			return $dom;
		}
	}
?>