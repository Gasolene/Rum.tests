<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\DropDownList;


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
	class CardTypeSelector extends DropDownList
	{
		/**
		 * Constructor
		 *
		 * @return void
		 * @access public
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );

			$this->items->add( 'Master Card', 'MC' );
			$this->items->add( 'Visa', 'VISA' );
			$this->items->add( 'American Express', 'AMEX' );
			$this->items->add( 'Diners Club', 'DinersClub' );
			$this->items->add( 'Discover', 'Discover' );
			$this->items->add( 'enRoute', 'enRoute' );
			$this->items->add( 'JCB', 'JCB' );
		}
	}
?>