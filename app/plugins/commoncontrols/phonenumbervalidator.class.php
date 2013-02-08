<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace CommonControls;
	use \System\Validators\PatternValidator;


	/**
	 * Provides basic validation for web controls
	 *
	 * @property string $controlId Control Id
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class PhoneNumberValidator extends PatternValidator
	{
		/**
		 * PhoneNumberValidator
		 *
		 * @param  string	$errorMessage	error message
		 * @return void
		 */
		public function __construct( $errorMessage = '' )
		{
			parent::__construct('^[1]?[- ]?[(]?[2-9]{1}[0-9]{2}[) -]{0,2}' . '[0-9]{3}[- ]?' . '[0-9]{4}[ ]?' . '((x|ext)[.]?[ ]?[0-9]{1,5})?$^', $errorMessage);
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			if($this->controlToValidate)
			{
				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_phone_number', 'must be a valid phone number');
			}
		}
	}
?>