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
	class CardSecurityCodeValidator extends ValidatorBase
	{
		/**
		 * card types
		 * @var array
		 */
		protected $cardTypes = array(
			array('MASTERCARD', 3),
			array('VISA', 3),
			array('AMEX', 4),
			array('DinersClub', 3),
			array('Discover', 3),
			array('enRoute', 0),
			array('JCB', 3)
		);


		/**
		 * CardSecurityCodeValidator
		 *
		 * @param  string	$errorMessage	error message
		 * @return void
		 */
		public function __construct( $errorMessage = '' )
		{
			parent::__construct($errorMessage);
		}


		/**
		 * validates control data, returns true on success
		 *
		 * @param  none
		 * @return void
		 * @access public
		 */
		public function validate()
		{
			if($this->controlToValidate)
			{
				$cardType = '';
				$cardName = '';

				foreach( $this->controlToValidate->getParentByType( '\System\Web\WebControls\Form' )->controls as $control )
				{
					if( $control instanceof CardTypeSelector )
					{
						$index = $control->items->indexOfItem($control->value);
						if($index > -1)
						{
							$cardType = $control->value;
							$cardName = $control->items->keyAt($index);
							break;
						}
					}
				}

				foreach($this->cardTypes as $cardTypeInfo)
				{
					if($cardType === $cardTypeInfo[0])
					{
						$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . str_replace('%x', $cardTypeInfo[1], str_replace('%y', $cardName, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_x_digit_card_security_code_for_y', 'must be a valid %x digit card security code for %y')));
						return $this->validateCVV2($this->controlToValidate->value, $cardTypeInfo[1]);
					}
				}

				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_card_security_code', 'must be a valid card security code');
				return $this->validateCVV2($this->controlToValidate->value);
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}


		/**
		 * validate cvv2 number
		 * @param string $cvv2		cvv2 number
		 * @param int    $length	length
		 * @return bool
		 */
		private function validateCVV2($cvv2, $length = 0)
		{
			if(\strlen($cvv2) === $length)
			{
				return !$length || \is_numeric($cvv2);
			}
			else
			{
				return !\strlen($cvv2) || (\strlen($cvv2) === 3 || \strlen($cvv2) === 4);
			}
		}
	}
?>