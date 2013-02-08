<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;


	/**
	 * Represents a Transaction Type
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class TransactionType
	{
		private $flags;

		private function  __construct($flags)
		{
			$this->flags = (int)$flags;
		}

		/**
		 * specifies a preauth transaction type
		 * @var TransactionType
		 */
		public static function preAuth() {return new TransactionType(1);}

		/**
		 * specifies a purchase transaction type
		 * @var TransactionType
		 */
		public static function purchase() {return new TransactionType(2);}

		/**
		 * specifies a preauth refund type
		 * @var TransactionType
		 */
		public static function refund() {return new TransactionType(4);}

		/**
		 * specifies a preauth void type
		 * @var TransactionType
		 */
		public static function void() {return new TransactionType(8);}
	}
?>