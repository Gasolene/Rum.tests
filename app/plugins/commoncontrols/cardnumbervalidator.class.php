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
	class CardNumberValidator extends ValidatorBase
	{
		/**
		 * card types
		 * @var array
		 */
		protected $cardTypes = array(
			array('MASTERCARD', array('51', '52', '53', '54', '55'), 16, 16, true),
			array('VISA', array('4'), 13, 16, true),
			array('AMEX', array('34', '37'), 15, 15, true),
			array('DinersClub', array('300', '301', '302', '303', '304', '305', '36', '38'), 14, 14, true),
			array('Discover', array('6011'), 16, 16, true),
			array('enRoute', array('2014', '2149'), 15, 15, false),
			array('JCB', array('3088', '3096', '3112', '3158', '3337', '3528', '2131', '1800'), 15, 16, true)
		);


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
				if($this->controlToValidate->value)
				{
					$cardType = '';
					$cardName = '';

					foreach( $this->controlToValidate->parent->controls as $control )
					{
						if( $control instanceof CardTypeSelector )
						{
							$index = $control->items->indexOfItem($control->value);
							if($index > -1)
							{
								$cardType = $control->value;
								$cardName = $control->items->keyAt($index);
							}
						}
					}

					$prefixes = array();
					foreach($this->cardTypes as $cardTypeInfo)
					{
						$prefixes = array_merge($prefixes, $cardTypeInfo[1]);

						if($cardType === $cardTypeInfo[0])
						{
							if($cardTypeInfo[2]==$cardTypeInfo[3])
							{
								$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . str_replace('%x', $cardTypeInfo[2], str_replace('%y', $cardName, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_x_digit_card_number_for_y', 'must be a valid %x digit card number for %y')));
							}
							else
							{
								$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . str_replace('%x', $cardTypeInfo[2], str_replace('%y', $cardTypeInfo[3], str_replace('%z', $cardName, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_x_to_y_digit_card_number_for_z', 'must be a valid %x to %y digit card number for %z'))));
							}

							return $this->validateCardNumber($this->controlToValidate->value, $cardTypeInfo[1], $cardTypeInfo[2], $cardTypeInfo[3], $cardTypeInfo[4]);
						}
					}

					$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_a_valid_card_number', 'must be a valid card number');
					return $this->validateCardNumber($this->controlToValidate->value, $prefixes);
				}
				else
				{
					return true;
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}


		/**
		 * validate card number
		 * @param string $cardNumber
		 * @param array $prefixes
		 * @param int $minLength
		 * @param int $maxLength
		 * @param bool $checkSum
		 * @return bool
		 */
		private function validateCardNumber($cardNumber, array $prefixes, $minLength = 13, $maxLength = 16, $checkSum = true)
		{
			// is numeric?
			if( is_numeric( $cardNumber ))
			{
				// check card length
				if( strlen( $cardNumber ) >= $minLength && strlen( $cardNumber ) <= $maxLength )
				{
					// check prefixes
					if( count( $prefixes ) > 0 )
					{
						$pass = false;

						foreach( $prefixes as $prefix )
						{
							if( substr( $cardNumber, 0, strlen( $prefix )) === $prefix )
							{
								$pass = true;
							}
						}
					}

					// checksum
					if( $pass && $checkSum )
					{
						return $this->luhnCheck( $cardNumber );
					}
					else
					{
						return $pass;
					}
				}
			}

			return false;
		}


		/**
		 * check mod 10 alg.
		 * @param string $number card number
		 * @return bool
		 */
		private function luhnCheck($number)
		{
			// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
			// Set the string length and parity
			$number_length=strlen($number);
			$parity=$number_length % 2;

			// Loop through each digit and do the maths
			$total=0;
			for ($i=0; $i<$number_length; $i++)
			{
				$digit=$number[$i];
				// Multiply alternate digits by two
				if ($i % 2 == $parity)
				{
					$digit*=2;
					// If the sum is two digits, add them together (in effect)
					if ($digit > 9)
					{
						$digit-=9;
					}
				}

				// Total up the digits
				$total+=$digit;
			}

			// If the total mod 10 equals 0, the number is valid
			return ($total % 10 == 0) ? TRUE : FALSE;
		}
	}
?>