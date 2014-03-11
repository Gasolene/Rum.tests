<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace CommonControls;
	use \System\Validators\ValidatorBase;


	/**
	 * Provides basic validation for web controls
	 *
	 * @property string $controlId Control Id
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class CardExpirationDateValidator extends ValidatorBase
	{
		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			if($this->controlToValidate)
			{
				$this->errorMessage = \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_date_in_the_future', 'must be a valid date in the future');
			}
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  string	$value	value to validate
		 * @return void
		 */
		public function validate($value)
		{
			return !$value || (time() <= strtotime($value));
		}
	}
?>