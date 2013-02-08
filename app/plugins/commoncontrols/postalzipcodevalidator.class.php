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
	class PostalZipCodeValidator extends ValidatorBase
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
				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_zip_postal_code', 'must be a valid zip/postal code');
			}
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @return void
		 */
		public function validate()
		{
			if($this->controlToValidate)
			{
				if(!$this->controlToValidate->value)
				{
					return true;
				}
				elseif(0 !== preg_match('^[0-9][0-9][0-9][0-9][0-9]$^', $this->controlToValidate->value))
				{
					return true;
				}
				elseif(0 !== preg_match('^[A-Za-z][0-9][A-Za-z]( )?[0-9][A-Za-z][0-9]$^', $this->controlToValidate->value))
				{
					return true;
				}
				return false;
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>