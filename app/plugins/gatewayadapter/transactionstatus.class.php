<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;


	/**
	 * Represents a Transaction Status
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class TransactionStatus
	{
		private $flags;

		private function  __construct($flags)
		{
			$this->flags = (int)$flags;
		}

		/**
		 * specifies an approved status
		 * @var TransactionStatus
		 */
		public static function approved() {return new TransactionStatus(1);}

		/**
		 * specifies a declined status
		 * @var TransactionStatus
		 */
		public static function declined() {return new TransactionStatus(2);}

		/**
		 * specifies an incomplete status
		 * @var TransactionStatus
		 */
		public static function incomplete() {return new TransactionStatus(4);}
	}
?>