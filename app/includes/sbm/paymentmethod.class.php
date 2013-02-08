<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Packages\Commerx\SBM;


	/**
	 * Represents a payment method
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		Commerx.SBM
	 */
	final class PaymentMethod
	{
		private $paymentmethod;

		private function  __construct($paymentmethod)
		{
			$this->paymentmethod = (string)$paymentmethod;
		}

		/**
		 * __toString
		 *
		 * @return string
		 * @ignore
		 */
		public function   __toString()
		{
			return $this->paymentmethod;
		}

		/**
		 * invoice
		 * @var PaymentMethod
		 */
		public static function invoice() {return new PaymentMethod("invoice");}

		/**
		 * creditCard
		 * @var PaymentMethod
		 */
		public static function creditCard() {return new PaymentMethod("creditcard");}
	}
?>