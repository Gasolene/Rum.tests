<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;

	use System\BadMemberCallException;
	use System\Base\InvalidOperationException;

	/**
	 * Represents a single Transaction
	 *
	 * @property-read	TransactionType $txnType Transaction Type
	 * @property-read	string $txnRefNo Transaction Ref No.
	 * @property-read	string $txnOrderNo Transaction Order No.
	 * @property-read	string $txnCustomerId Transaction Customer Id
	 * @property-read	float $txnShippingAmount Transaction Shipping Amount
	 * @property-read	string $txnComments Transaction Comments
	 * @property-read	TransactionTaxCollection $txnTaxes Transaction Tax Collection
	 * @property-read	TransactionItemCollection $txnItems	Transaction Item Collection
	 * @property-read	string $customerFirstName Customer First Name
	 * @property-read	string $customerLastName Customer Last Name
	 * @property-read	string $customerCompany Customer Company
	 * @property-read	string $customerPhoneNumber Customer Phone No.
	 * @property-read	string $customerEmail Customer E-mail Address
	 * @property-read	string $billingAddress Billing Address
	 * @property-read	string $billingCity Billing City
	 * @property-read	string $billingProvince Billing Province
	 * @property-read	string $billingPostalCode Billing Postal Code
	 * @property-read	string $billingCountry Billing Country
	 * @property-read	string $shippingAddress Shipping Address
	 * @property-read	string $shippingCity Shipping City
	 * @property-read	string $shippingProvince Shipping Province
	 * @property-read	string $shippingPostalCode Shipping Postal Code
	 * @property-read	string $shippingCountry Shipping Country
	 * @property-read	string $creditCardHolder Credit Card Holder
	 * @property-read	string $creditCardType Credit Card Type
	 * @property-read	string $creditCardNumber Credit Card No.
	 * @property-read	string $creditCardExpiryMonth Credit Card Expiration Month
	 * @property-read	string $creditCardExpiryYear Credit Card Expiration Year
	 * @property-read	string $creditCardCVV Credit Card CVV
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class Transaction
	{
		/**
		 * Customer First Name
		 * @access protected
		 * @var string
		**/
		protected $customerFirstName		= "";

		/**
		 * Customer Last Name
		 * @access protected
		 * @var string
		**/
		protected $customerLastName			= "";

		/**
		 * Customer Company
		 * @access protected
		 * @var string
		**/
		protected $customerCompany			= "";

		/**
		 * Customer Phone Number
		 * @access protected
		 * @var string
		**/
		protected $customerPhoneNumber		= "";

		/**
		 * Customer Email
		 * @access protected
		 * @var string
		**/
		protected $customerEmail			= "";

		/**
		 * Billing  Address
		 * @access protected
		 * @var string
		**/
		protected $billingAddress			= "";

		/**
		 * Billing City
		 * @access protected
		 * @var string
		**/
		protected $billingCity				= "";

		/**
		 * Billing Province
		 * @access protected
		 * @var string
		**/
		protected $billingProvince			= "";

		/**
		 * Billing Postal Code
		 * @access protected
		 * @var string
		**/
		protected $billingPostalCode		= "";

		/**
		 * Billing Country
		 * @access protected
		 * @var string
		**/
		protected $billingCountry			= "";

		/**
		 * Shipping Address
		 * @access protected
		 * @var string
		**/
		protected $shippingAddress			= "";

		/**
		 * Shipping City
		 * @access protected
		 * @var string
		**/
		protected $shippingCity				= "";

		/**
		 * Shipping Province
		 * @access protected
		 * @var string
		**/
		protected $shippingProvince			= "";

		/**
		 * Shipping Postal Code
		 * @access protected
		 * @var string
		**/
		protected $shippingPostalCode		= "";

		/**
		 * Shipping Country
		 * @access protected
		 * @var string
		**/
		protected $shippingCountry			= "";

		/**
		 * Credit Card Holder
		 * @access protected
		 * @var string
		**/
		protected $creditCardHolder			= "";

		/**
		 * Credit Card Type
		 * @access protected
		 * @var string
		**/
		protected $creditCardType			= "";

		/**
		 * Credit Card No
		 * @access protected
		 * @var string
		**/
		protected $creditCardNumber			= "";

		/**
		 * Credit Card Expiration Month
		 * @access protected
		 * @var string
		**/
		protected $creditCardExpiryMonth	= "";

		/**
		 * Credit Card Expiration Year
		 * @access protected
		 * @var string
		**/
		protected $creditCardExpiryYear		= "";

		/**
		 * Credit Card CVV
		 * @access protected
		 * @var string
		**/
		protected $creditCardCVV			= "";

		/**
		 * Transaction Type
		 * @access protected
		 * @var TransactionType
		**/
		protected $txnType					= "";

		/**
		 * Transaction Reference No
		 * @access protected
		 * @var string
		**/
		protected $txnRefNo					= "";

		/**
		 * Order No
		 * @access protected
		 * @var string
		**/
		protected $txnOrderNo				= "";

		/**
		 * Customer Id
		 * @access protected
		 * @var string
		**/
		protected $txnCustomerId			= "";

		/**
		 * Shipping Amount
		 * @access protected
		 * @var float
		**/
		protected $txnShippingAmount		= 0;

		/**
		 * Comments
		 * @access protected
		 * @var string
		**/
		protected $txnComments				= "";

		/**
		 * Taxes
		 * @access protected
		 * @var TransactionTaxCollection
		**/
		protected $txnTaxes;

		/**
		 * Items
		 * @access protected
		 * @var TransactionItemCollection
		**/
		protected $txnItems;

		/**
		 * is locked
		 * @access private
		 * @var bool
		**/
		private $locked						= false;


		/**
		 * Constructor
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct()
		{
			$this->txnTaxes = new TransactionTaxCollection();
			$this->txnItems = new TransactionItemCollection();
		}


		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 *
		 * @return mixed
		 * @access public
		 * @ignore
		 */
		public function __get($field)
		{
			if($field=="locked")
			{
				throw new BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
			elseif($field=="txnTaxes")
			{
				return clone $this->txnTaxes;
			}
			elseif($field=="txnItems")
			{
				return clone $this->txnItems;
			}
			elseif(in_array($field, array_keys(get_object_vars($this))))
			{
				return $this->{$field};
			}
			else
			{
				throw new BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * set customer data
		 *
		 * @param  string		$firstName		First Name
		 * @param  string		$lastName		Last Name
		 * @param  string		$company		Company
		 * @param  string		$phoneNumber	Phone Number
		 * @param  string		$email			E-Mail Address
		 *
		 * @return void
		 * @access public
		 */
		public function setCustomerDetails($firstName, $lastName, $company, $phoneNumber, $email)
		{
			if(!$this->locked)
			{
				$this->customerFirstName = (string)$firstName;
				$this->customerLastName = (string)$lastName;
				$this->customerCompany = (string)$company;
				$this->customerPhoneNumber = (string)$phoneNumber;
				$this->customerEmail = (string)$email;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * set billing data
		 *
		 * @param  string		$address			Address
		 * @param  string		$city				City
		 * @param  string		$province			Province
		 * @param  string		$country			Country
		 * @param  string		$postalCode			Postal Code
		 * 
		 * @return void
		 * @access public
		 */
		public function setBillingDetails($address, $city, $province, $country, $postalCode)
		{
			if(!$this->locked)
			{
				$this->billingAddress = (string)$address;
				$this->billingCity = (string)$city;
				$this->billingProvince = (string)$province;
				$this->billingCountry = (string)$country;
				$this->billingPostalCode = (string)$postalCode;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * set shipping data
		 * 
		 * @param  string		$address			Address
		 * @param  string		$city				City
		 * @param  string		$province			Province
		 * @param  string		$country			Country
		 * @param  string		$postalCode			Postal Code
		 *
		 * @return void
		 * @access public
		 */
		public function setShippingDetails($address, $city, $province, $country, $postalCode)
		{
			if(!$this->locked)
			{
				$this->shippingAddress = (string)$address;
				$this->shippingCity = (string)$city;
				$this->shippingProvince = (string)$province;
				$this->shippingCountry = (string)$country;
				$this->shippingPostalCode = (string)$postalCode;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * set cc data
		 *
		 * @param  string		$cardHolder			Card Holder
		 * @param  string		$cardNumber			Card Number
		 * @param  string		$expiryMonth		Expiration Month
		 * @param  string		$expiryYear			Expiration Year
		 * @param  string		$CVV				CVV
		 * @param  string		$cardType			Card Type
		 * 
		 * @return void
		 * @access public
		 */
		public function setCreditCardDetails($cardHolder, $cardNumber, $expiryMonth, $expiryYear, $CVV, $cardType = "")
		{
			if(!$this->locked)
			{
				if(strtotime("{$expiryYear}-{$expiryMonth}-01") === false)
				{
					throw new InvalidOperationException("invalid expiration date");
				}

				$this->creditCardHolder = (string)$cardHolder;
				$this->creditCardNumber = (string)$cardNumber;
				$this->creditCardExpiryMonth = (string)$expiryMonth;
				$this->creditCardExpiryYear = (string)$expiryYear;
				$this->creditCardCVV = (string)$CVV;
				$this->creditCardType = (string)$cardType;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * set transaction reference no
		 *
		 * @param  TransactionType		$type				Trasnaction Type
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionType(TransactionType $type)
		{
			if(!$this->locked)
			{
				$this->txnType = $type;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * set transaction reference no
		 *
		 * @param  string		$refNo				Reference No
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionRefNo($refNo)
		{
			if(!$this->locked)
			{
				$this->txnRefNo = (string)$refNo;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * set transaction data
		 *
		 * @param  string		$orderNo			Order No
		 * @param  string		$customerId			Customer Id
		 * @param  float		$shippingAmount		Shipping Amount
		 * @param  string		$comments			Comments
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionDetails($orderNo, $customerId, $shippingAmount, $comments)
		{
			if(!$this->locked)
			{
				$this->txnOrderNo = (string)$orderNo;
				$this->txnCustomerId = (string)$customerId;
				$this->txnShippingAmount = (float)$shippingAmount;
				$this->txnComments = (string)$comments;
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * add tax item
		 *
		 * @param  string		$productCode			Product Code
		 * @param  string		$description			Description
		 * @param  int			$qty					Quantity
		 * @param  float		$price					Price
		 *
		 * @return void
		 * @access public
		 */
		public function addItem($productCode, $description, $qty, $price)
		{
			if(!$this->locked)
			{
				$this->txnItems->add(new TransactionItem($productCode,$description,$qty,$price));
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * add tax item
		 *
		 * @param  string		$name			Tax Name
		 * @param  float		$amount			Tax Amount
		 *
		 * @return void
		 * @access public
		 */
		public function addTax($name, $amount)
		{
			if(!$this->locked)
			{
				$this->txnTaxes->add(new TransactionTax($name,$amount));
			}
			else
			{
				throw new InvalidOperationException("Transaction has already been processed and cannot be modified");
			}
		}


		/**
		 * get subtotal
		 * 
		 * @return real
		 * @access public
		 */
		public function getSubtotal()
		{
			$subtotal = 0;
			foreach($this->txnItems as $item)
			{
				$subtotal += $item->getQuantity() * $item->getAmount();
			}
			return $subtotal;
		}


		/**
		 * get total
		 * 
		 * @return real
		 * @access public
		 */
		public function getTotal()
		{
			$total = $this->getSubtotal();
			$total += $this->txnShippingAmount;
			foreach($this->txnTaxes as $tax)
			{
				$total += $tax->getAmount();
			}
			return $total;
		}


		/**
		 * lock for no more editing
		 *
		 * @return void
		 * @access public
		 */
		public function lock()
		{
			if($this->txnType instanceof TransactionType)
			{
				$this->locked = true;
			}
			else
			{
				throw new InvalidOperationException("Transaction type is null");
			}
		}
	}
?>