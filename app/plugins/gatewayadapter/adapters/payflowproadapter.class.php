<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace GatewayAdapter;


	/**
	 * PayFlow Pro Adapter
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class PayFlowProAdapter implements IGatewayAdapter
	{
		/**
		 * username
		 * @access protected
		 * @var string
		**/
		protected $username		= "";

		/**
		 * password
		 * @access protected
		 * @var string
		**/
		protected $password		= "";

		/**
		 * partner
		 * @access protected
		 * @var string
		**/
		protected $partner		= "";

		/**
		 * vender
		 * @access protected
		 * @var string
		**/
		protected $vender		= "";

		/**
		 * test mode
		 * @access protected
		 * @var string
		**/
		protected $testMode		= false;

		/**
		 * country code
		 * @access protected
		 * @var string
		**/
		protected $countryCode	= "CAD";

		/**
		 * currency code
		 * @access protected
		 * @var string
		**/
		protected $currencyCode	= "CAD";

		/**
		 * use proxy
		 * @access protected
		 * @var bool
		**/
		protected $useProxy		= false;

		/**
		 * proxy host
		 * @access protected
		 * @var string
		**/
		protected $proxyHost	= "127.0.0.1";

		/**
		 * proxy port
		 * @access protected
		 * @var int
		**/
		protected $proxyPort	= 808;

		/**
		 * URL
		 * @var string
		**/
		const url				= "https://payflowpro.paypal.com";

		/**
		 * test URL
		 * @var string
		**/
		const test_url			= "https://pilot-payflowpro.paypal.com";

		/**
		 * api version
		 * @var string
		**/
		const apiVersion		= "59.0";

		/**
		 * timeout
		 * @var int
		**/
		const timeout			= 30;


		/**
		 * Constructor
		 *
		 * @param	string		$username		Payflow Pro username
		 * @param	string		$password		Payflow Pro password
		 * @param	string		$partner		Payflow Pro partner
		 * @param	string		$vender			Payflow Pro vender
		 * @param	bool		$testMode		Enable test mode (Optional)
		 * @param	string		$countryCode	Country Code (Optional)
		 * @param	string		$currencyCode	CurrentyCode (Optional)
		 * @param	bool		$useProxy		Specifies Whether to use Proxy (Optional)
		 * @param	string		$proxyHost		Proxy Host (Optional)
		 * @param	int			$proxyPort		Proxy Port (Optional)
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct($username, $password, $partner, $vender, $testMode = false, $countryCode = "CAD", $currencyCode = "CAD", $useProxy = false, $proxyHost = "127.0.0.1", $proxyPort = 808)
		{
			$this->username = (string)$username;
			$this->password = (string)$password;
			$this->partner = (string)$partner;
			$this->vender = (string)$vender;

			$this->testMode = (bool)$testMode;
			$this->countryCode = (string)$countryCode;
			$this->currencyCode = (string)$currencyCode;
			$this->useProxy = (bool)$useProxy;
			$this->proxyHost = (string)$proxyHost;
			$this->proxyPort = (int)$proxyPort;
		}


		/**
		 * process transaction
		 *
		 * @param  Transaction	$transaction	Transaction
		 *
		 * @return GatewayResponse
		 * @access public
		 */
		public function process(Transaction &$transaction)
		{
			if($transaction->txnType == TransactionType::purchase())
			{
				$parameters = array(
					'TRXTYPE' => 'S',
					'TENDER' => 'C',
					'AMT' => $transaction->getTotal(),
					'ACCT' => $transaction->creditCardNumber,
					'EXPDATE' => str_pad($transaction->creditCardExpiryMonth, 2, '0', STR_PAD_LEFT).$transaction->creditCardExpiryYear,
					'CVV2' => $transaction->creditCardCVV,
					'FIRSTNAME' => $transaction->customerFirstName,
					'LASTNAME' => $transaction->customerLastName,
					'STREET' => $transaction->billingAddress,
					'CITY' => $transaction->billingCity,
					'STATE' => $transaction->billingProvince,
					'ZIP' => $transaction->billingPostalCode,
					'COUNTRYCODE' => $this->countryCode,
					'CURRENCYCODE' => $this->currencyCode
				);

				return $this->send($transaction, $parameters);
			}
			else
			{
				throw new InvalidOperationException("Transaction type not supported");
			}
		}


		/**
		 * perform create recurring payment operation
		 *
		 * @param  Transaction	$transaction	Transaction object
		 * @param  array		$parameters		Associative array of key value pairs
		 *
		 * @return GatewayResponse
		 * @access private
		 */
		private function send(Transaction $transaction, array $parameters)
		{
			$transaction->lock();

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->testMode?self::test_url:self::url);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);

			if($this->useProxy)
			{
				curl_setopt ($ch, CURLOPT_PROXY, $this->proxyHost.":".$this->proxyPort);
			}

			$nvpreq = "VERSION=".urlencode($this->apiVersion)."&PARTNER=".urlencode($this->partner)."&VENDOR=".urlencode($this->vender)."&USER=".urlencode($this->username)."&PWD=".urlencode($this->password);
			foreach($parameters as $key => $value)
			{
				$nvpreq .= "&{$key}=" . urlencode($value);
			}

			curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

			$response = curl_exec($ch);

			if (curl_errno($ch))
			{
				throw new \Exception(curl_error($ch));
			}
			else
			{
				curl_close($ch);
			}

			$nvpReqArray = $this->deformatNVP($nvpreq);
			$nvpResArray = $this->deformatNVP($response);

			$gatewayResponse = new GatewayResponse();
			$gatewayResponse->setTransactionType($nvpReqArray["TRXTYPE"]);
			$gatewayResponse->setTransactionDate(date('Y-m-d'));
			$gatewayResponse->setTransactionTime(date('g:ia'));
			$gatewayResponse->setAmount($transaction->getTotal());
			$gatewayResponse->setOrderNo($transaction->txnOrderNo);
			$gatewayResponse->setReferenceNo($nvpResArray["PNREF"]);
			$gatewayResponse->setAuthCode($nvpResArray["AUTHCODE"]);
			//$gatewayResponse->setISOCode();
			$gatewayResponse->setResponseCode($nvpResArray["RESULT"]);
			$gatewayResponse->setResponseMessage($nvpResArray["RESPMSG"]);
			$gatewayResponse->setTransactionStatus($nvpResArray["RESULT"]==0?TransactionStatus::approved():TransactionStatus::declined());
			$gatewayResponse->setAVSResult($nvpResArray["AVSADDR"].$nvpResArray["AVSZIP"]);
			$gatewayResponse->setCVVResult($nvpResArray["CVV2MATCH"]);
			$gatewayResponse->lock();

			return $gatewayResponse;
		}


		/**
		 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
		 * It is usefull to search for a particular key and displaying arrays.
		 *
		 * @param  string $nvpstr the NVPString.
		 * @return array nvpArray as an Associative Array.
		 * @access private
		 */
		private function deformatNVP($nvpstr)
		{
			$intial=0;
			$nvpArray = array();

			while(strlen($nvpstr))
			{
				//postion of Key
				$keypos= strpos($nvpstr,'=');
				//position of value
				$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

				/*getting the Key and Value values and storing in a Associative Array*/
				$keyval=substr($nvpstr,$intial,$keypos);
				$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
				//decoding the respose
				$nvpArray[urldecode($keyval)] =urldecode( $valval);
				$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
			}
			return $nvpArray;
		}
	}
?>