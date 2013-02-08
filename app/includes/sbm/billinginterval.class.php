<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Packages\Commerx\SBM;


	/**
	 * Represents a billing interval
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		Commerx.SBM
	 */
	final class BillingInterval
	{
		private $interval;

		private function  __construct($interval)
		{
			$this->interval = (string)$interval;
		}

		/**
		 * __toString
		 *
		 * @return string
		 * @ignore
		 */
		public function   __toString()
		{
			return $this->interval;
		}

		/**
		 * day
		 * @var BillingInterval
		 */
		public static function day() {return new BillingInterval("d");}

		/**
		 * week
		 * @var BillingInterval
		 */
		public static function week() {return new BillingInterval("w");}

		/**
		 * month
		 * @var BillingInterval
		 */
		public static function month() {return new BillingInterval("m");}

		/**
		 * year
		 * @var BillingInterval
		 */
		public static function year() {return new BillingInterval("y");}
	}
?>