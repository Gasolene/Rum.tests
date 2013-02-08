<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\RadioButtonList;

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
	class QuadrantSelector extends \System\Web\WebControls\DropDownList
	{
		/**
		 * Constructor
		 *
		 * The constructor sets attributes based on session data, triggering events, and is responcible for 
		 * formatting the proper request value and garbage handling
		 *
		 * @return void
		 * @access public
		 */
		public function __construct( $controlId, $default = 'NW' ) {
			parent::__construct( $controlId, $default );
		}


		/**
		 * called when control is loaded
		 * 
		 * @return bool			true if successfull
		 * @access public
		 */
		public function onLoad() {
			parent::onLoad();

			$this->items->add( 'NW', 'NW' );
			$this->items->add( 'NE', 'NE' );
			$this->items->add( 'SW', 'SW' );
			$this->items->add( 'SE', 'SE' );
		}
	}
?>