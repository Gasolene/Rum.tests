<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;

	use System\BadMemberCallException;
	use System\Base\InvalidOperationException;

	/**
	 * Represents a Gateway Response
	 *
	 * @property-read	string $cardType Card Type
	 * @property-read	string $transactionType Transaction Type
	 * @property-read	string $date Transaction Date
	 * @property-read	string $time Transaction Time
	 * @property-read	TransactionStatus $status Response Status
	 * @property-read	float $amount Amount
	 * @property-read	string $orderNo Order No.
	 * @property-read	string $refNo Reference No.
	 * @property-read	string $authCode Authorization Code
	 * @property-read	string $ISOCode ISO Code
	 * @property-read	string $responseCode Response Code
	 * @property-read	string $responseMessage Response Message
	 * @property-read	string $AVSResult AVS Result
	 * @property-read	string $CVVResult CVV Result
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class GatewayResponse
	{
		/**
		 * card type
		 * @access protected
         * @var string
		**/
		protected $cardType			= "";

		/**
		 * transaction type
		 * @access protected
         * @var string
		**/
		protected $transactionType	= "";

		/**
		 * transaction date
		 * @access protected
		 * @var string
		**/
		protected $date				= "";

		/**
		 * transaction time
		 * @access protected
		 * @var string
		**/
		protected $time				= "";

		/**
		 * response status
		 * @access protected
		 * @var int
		**/
		protected $status;

		/**
		 * response message
		 * @access protected
		 * @var int
		**/
		protected $amount			= 0;

		/**
		 * order no
		 * @access protected
		 * @var string
		**/
		protected $orderNo			= "";

		/**
		 * reference no
		 * @access protected
		 * @var string
		**/
		protected $refNo			= "";

		/**
		 * auth code
		 * @access protected
		 * @var string
		**/
		protected $authCode			= "";

		/**
		 * ISO code
		 * @access protected
		 * @var string
		**/
		protected $ISOCode			= "";

		/**
		 * response code
		 * @access protected
		 * @var string
		**/
		protected $responseCode		= "";

		/**
		 * response message
		 * @access protected
		 * @var string
		**/
		protected $responseMessage	= "";

		/**
		 * avs address pass status
		 * @access protected
		 * @var bool
		**/
		protected $AVSResult		= "";

		/**
		 * cvv pass status
		 * @access protected
		 * @var bool
		**/
		protected $CVVResult		= "";

		/**
		 * is locked
		 * @access private
		 * @var bool
		**/
		private $locked				= false;


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
			if($field=='locked')
			{
				throw new BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
			elseif($field=='status')
			{
				return $this->getStatus();
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
		 * set card type
		 *
		 * @param	string		$cardType	Card Type
		 *
		 * @return void
		 * @access public
		 */
		public function setCardType($cardType)
		{
			if(!$this->locked)
			{
				return $this->cardType = $cardType;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set transaction type
		 *
		 * @param	string		$transactionType	Transaction Type
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionType($transactionType)
		{
			if(!$this->locked)
			{
				return $this->transactionType = $transactionType;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set transaction date
		 *
		 * @param	string		$date	Transaction Date
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionDate($date)
		{
			if(!$this->locked)
			{
				return $this->date = $date;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set transaction time
		 *
		 * @param	string		$time	Transaction Time
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionTime($time)
		{
			if(!$this->locked)
			{
				return $this->time = $time;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set transaction status
		 *
		 * @param  TransactionStatus	$status		Transaction Status
		 *
		 * @return void
		 * @access public
		 */
		public function setTransactionStatus(TransactionStatus $status)
		{
			if(!$this->locked)
			{
				return $this->status = $status;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set reference no
		 *
		 * @param	string		$refNo		Reference No.
		 *
		 * @return void
		 * @access public
		 */
		public function setReferenceNo($refNo)
		{
			if(!$this->locked)
			{
				return $this->refNo = $refNo;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set order no
		 *
		 * @param	string		$orderNo		Order No.
		 *
		 * @return void
		 * @access public
		 */
		public function setOrderNo($orderNo)
		{
			if(!$this->locked)
			{
				return $this->orderNo = $orderNo;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set sequence no
		 *
		 * @param	string		$sequenceNo		Sequence No.
		 *
		 * @return void
		 * @access public
		 */
		public function setSequenceNo($sequenceNo)
		{
			if(!$this->locked)
			{
				return $this->sequenceNo = $sequenceNo;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set auth code
		 *
		 * @param	string		$authCode		Authorization Code
		 *
		 * @return void
		 * @access public
		 */
		public function setAuthCode($authCode)
		{
			if(!$this->locked)
			{
				return $this->authCode = $authCode;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set ISO code
		 *
		 * @param	string		$ISOCode		ISO Code
		 *
		 * @return void
		 * @access public
		 */
		public function setISOCode($ISOCode)
		{
			if(!$this->locked)
			{
				return $this->ISOCode = $ISOCode;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set response code
		 *
		 * @param	string		$responseCode		Response Code
		 *
		 * @return void
		 * @access public
		 */
		public function setResponseCode($responseCode)
		{
			if(!$this->locked)
			{
				return $this->responseCode = $responseCode;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set response message
		 *
		 * @param	string		$responseMessage		Response Message
		 *
		 * @return void
		 * @access public
		 */
		public function setResponseMessage($responseMessage)
		{
			if(!$this->locked)
			{
				return $this->responseMessage = $responseMessage;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set AVS result
		 *
		 * @param	string		$avsResult		AVS Result
		 *
		 * @return void
		 * @access public
		 */
		public function setAVSResult($avsResult)
		{
			if(!$this->locked)
			{
				return $this->AVSResult = $avsResult;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set CVV result
		 *
		 * @param	string		$cvvResult		CVV Result
		 *
		 * @return void
		 * @access public
		 */
		public function setCVVResult($cvvResult)
		{
			if(!$this->locked)
			{
				return $this->CVVResult = $cvvResult;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * set amount
		 *
		 * @param	float		$amount		Amount
		 *
		 * @return void
		 * @access public
		 */
		public function setAmount($amount)
		{
			if(!$this->locked)
			{
				return $this->amount = (float)$amount;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has been locked and cannot be modified");
			}
		}


		/**
		 * return status of transaction
		 *
		 * @return int
		 * @access public
		 */
		public function getStatus()
		{
			if($this->locked)
			{
				return $this->status;
			}
			else
			{
				throw new InvalidOperationException("GatewayResponse has not been populated");
			}
		}


		/**
		 * lock object for no more editing
		 * 
		 * @return void
		 * @access public
		 */
		public function lock()
		{
			if($this->status instanceof TransactionStatus)
			{
				$this->locked = true;
			}
			else
			{
				throw new InvalidOperationException("Cannot lock GatewayResponse, response status is null");
			}
		}
	}
?>