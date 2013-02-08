<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace ColorPicker;
	use \System\Validators\ValidatorBase;


	/**
	 * Provides basic validation for web controls
	 *
	 * @property string $controlId Control Id
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class ColorValidator extends ValidatorBase
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
				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_color', 'must be a valid color');
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
				elseif(0 !== preg_match('^#[0-9a-fA-F]{6}$^', $this->controlToValidate->value))
				{
					return true;
				}
				elseif(0 !== preg_match('^rgb\([0-9]{1,3}, [0-9]{1,3}, [0-9]{1,3}\)$^', $this->controlToValidate->value))
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