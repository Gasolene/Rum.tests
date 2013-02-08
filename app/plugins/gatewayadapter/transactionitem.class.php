<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;

	use System\Base\InvalidOperationException;

	/**
	 * Represents a Transaction Item
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class TransactionItem
	{
		/**
		 * item code
		 * @access protected
		 * @var string
		**/
		protected $code				= "";

		/**
		 * item description
		 * @access protected
		 * @var string
		**/
		protected $description		= "";

		/**
		 * item qty
		 * @access protected
		 * @var int
		**/
		protected $qty				= 0;

		/**
		 * item amount
		 * @access protected
		 * @var float
		**/
		protected $amount			= 0;


		/**
		 * Constructor
		 *
		 * @param	string	$productCode	Product Code
		 * @param	string	$description	Product Description
		 * @param	int		$qty			Quantity
		 * @param	float	$amount			Amount
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct( $productCode = "", $description = "", $qty = 0, $amount = 0 )
		{
			$this->setProductCode($productCode);
			$this->setDescription($description);
			$this->setQuantity($qty);
			$this->setAmount($amount);
		}


		/**
		 * set description
		 *
		 * @param	string	$description		Item Description
		 *
		 * @return	void
		 * @access	public
		 */
		public function setDescription( $description )
		{
			$this->description = (string)$description;
		}


		/**
		 * get description
		 *
		 * @return	string
		 * @access	public
		 */
		public function getDescription()
		{
			return $this->description;
		}


		/**
		 * set product code
		 *
		 * @param	string	$code		Item Code
		 *
		 * @return	void
		 * @access	public
		 */
		public function setProductCode( $code )
		{
			$this->code = (string)$code;
		}


		/**
		 * get product code
		 *
		 * @return	string
		 * @access	public
		 */
		public function getProductCode()
		{
			return $this->code;
		}


		/**
		 * set quantity
		 *
		 * @param	int		$qty		Item Quantity
		 *
		 * @return	void
		 * @access	public
		 */
		public function setQuantity( $qty )
		{
			if(is_int($qty))
			{
				$this->qty = (int)$qty;
			}
			else
			{
				throw new InvalidOperationException("qty is not a valid integer");
			}
		}


		/**
		 * get quantity
		 *
		 * @return	int
		 * @access	public
		 */
		public function getQuantity()
		{
			return $this->qty;
		}


		/**
		 * set amount
		 *
		 * @param	float	$amount		Item Amount
		 *
		 * @return	void
		 * @access	public
		 */
		public function setAmount( $amount )
		{
			if(is_numeric($amount))
			{
				$this->amount = (float)$amount;
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