<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\Text;


	/**
     * handles element creation and event handling
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class PasswordInput extends Text
	{
		/**
		 * Max Length of control when defined
		 * @access public
		 */
		protected $maxLength				= 16;

		/**
		 * if this property is True characters will be masked.
		 * @access public
		 */
		protected $mask						= true;

		/**
		 * if this property is True characters will be masked.
		 * @access public
		 */
		protected $enableViewState			= false;


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->addValidator(new \System\Validators\PatternValidator('^(?=.*\d).{6,16}$^', $this->label . ' ' . \System\Base\ApplicationBase::getInstance()->translator->get('must_contain_x_characters_with_one_numeric', 'must contain 6 to 16 characters with at least one numeric digit')));
		}
	}
?>