<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;

	use System\Base\InvalidOperationException;

	/**
	 * Represents a Transaction Tax
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class TransactionTax
	{
		/**
		 * tax name
		 * @access protected
		 * @var string
		**/
		protected $name				= "";

		/**
		 * tax amount
		 * @access protected
		 * @var float
		**/
		protected $amount			= 0;


		/**
		 * Constructor
		 *
		 * @param	string		$name		Tax Name
		 * @param	float		$amount		Tax Amount
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct( $name = "", $amount = 0 )
		{
			$this->setName($name);
			$this->setAmount($amount);
		}


		/**
		 * set name
		 *
		 * @param	string		$name		Tax Name
		 *
		 * @return	void
		 * @access	public
		 */
		public function setName( $name )
		{
			$this->name = (string)$name;
		}


		/**
		 * get name
		 *
		 * @return	string
		 * @access	public
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * set amount
		 *
		 * @param	float		$amount		Tax Amount
		 * 
		 * @return	void
		 * @access	public
		 */
		public function setAmount( $amount )
		{
			if(is_numeric($amount))
			{
				$this->amount = (real)$amount;
			}
			else
			{
				throw new InvalidOperationException("amount is not a valid number");
			}
		}


		/**
		 * get amount
		 *
		 * @return	real
		 * @access	public
		 */
		public function getAmount()
		{
			return $this->amount;
		}
	}
?>