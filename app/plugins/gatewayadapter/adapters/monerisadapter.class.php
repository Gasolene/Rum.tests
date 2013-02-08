<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace GatewayAdapter;

	use \Moneris\mpgCustInfo;
	use \Moneris\mpgHttpsPost;
	use \Moneris\mpgResponse;
	use \Moneris\mpgRequest;
	use \Moneris\mpgRecur;
	use \Moneris\mpgTransaction;
	use \Moneris\mpgAvsInfo;
	use \Moneris\mpgCvdInfo;

	/**
	 * include api
	 */
	require_once __PLUGINS_PATH__ . '/gatewayadapter/api\'s/moneris/mpgClasses.php';

	function moneris_error() {}

	/**
	 * Moneris Adapter
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class MonerisAdapter implements IGatewayAdapter
	{
		/**
		 * store id
		 * @access protected
		 * @var string
		**/
		protected $storeId		= "store1";

		/**
		 * api token
		 * @access protected
		 * @var string
		**/
		protected $apiToken		= "yesguy";

		/**
		 * protocol
		 * @var string
		**/
		const protocol			= "https";

		/**
		 * host
		 * @var string
		**/
		const host				= "www3.moneris.com";

		/**
		 * test host
		 * @var string
		**/
		const test_host			= "esqa.moneris.com";

		/**
		 * port
		 * @var int
		**/
		const port				= 443;

		/**
		 * file
		 * @var string
		**/
		const file				= "/gateway2/servlet/MpgRequest";

		/**
		 * api version
		 * @var string
		**/
		const apiVersion		= "MpgApi Version 2.03(php)";

		/**
		 * timeout
		 * @var int
		**/
		const timeout			= 60;


		/**
		 * Constructor
		 *
		 * @param	string		$storeId		Moneris store Id
		 * @param	string		$apiToken		Moneris store API Token
		 * @param	bool		$testMode		Enable test mode
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct($storeId, $apiToken, $testMode = false)
		{
			$this->storeId = (string)$storeId;
			$this->apiToken = (string)$apiToken;

			// define configuration
			$GLOBALS["moneris_globals"] = array (
                  'MONERIS_PROTOCOL' => self::protocol,
                  'MONERIS_HOST' => (bool)$testMode?self::test_host:self::host,
                  'MONERIS_PORT' => (string)self::port,
                  'MONERIS_FILE' => self::file,
                  'API_VERSION'  => self::apiVersion,
                  'CLIENT_TIMEOUT' => (string)self::timeout
                 );
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
			set_error_handler('\GatewayAdapter\moneris_error');
			$gatewayResponse = new GatewayResponse();

			if($transaction->txnType == TransactionType::purchase())
			{
				$type = 'purchase';
				$mpgCustInfo = new \mpgCustInfo();

				$mpgCustInfo->setBilling(array(
					'first_name' => $transaction->customerFirstName,
					'last_name' => $transaction->customerLastName,
					'company_name' => $transaction->customerCompany,
					'address' => $transaction->billingAddress,
					'city' => $transaction->billingCity,
					'province' => $transaction->billingProvince,
					'postal_code' => $transaction->billingPostalCode,
					'country' => $transaction->billingCountry,
					'phone_number' => $transaction->customerPhoneNumber,
					'fax' => "",
					'tax1' => number_format((isset($transaction->txnTaxes[0])?$transaction->txnTaxes[0]->getAmount():""), 2, '.', ''),
					'tax2' => number_format((isset($transaction->txnTaxes[1])?$transaction->txnTaxes[1]->getAmount():""), 2, '.', ''),
					'tax3' => number_format((isset($transaction->txnTaxes[2])?$transaction->txnTaxes[2]->getAmount():""), 2, '.', ''),
					'shipping_cost' => number_format($transaction->txnShippingAmount, 2, '.', '')
				));

				if(strlen($transaction->shippingAddress)>0)
				{
					$mpgCustInfo->setShipping(array(
						'first_name' => $transaction->customerFirstName,
						'last_name' => $transaction->customerLastName,
						'company_name' => $transaction->customerCompany,
						'address' => $transaction->shippingAddress,
						'city' => $transaction->shippingCity,
						'province' => $transaction->shippingProvince,
						'postal_code' => $transaction->shippingPostalCode,
						'country' => $transaction->shippingCountry,
						'phone_number' => $transaction->customerPhoneNumber,
						'fax' => "",
						'tax1' => number_format((isset($transaction->txnTaxes[0])?$transaction->txnTaxes[0]->getAmount():""), 2, '.', ''),
						'tax2' => number_format((isset($transaction->txnTaxes[1])?$transaction->txnTaxes[1]->getAmount():""), 2, '.', ''),
						'tax3' => number_format((isset($transaction->txnTaxes[2])?$transaction->txnTaxes[2]->getAmount():""), 2, '.', ''),
						'shipping_cost' => number_format($transaction->txnShippingAmount, 2, '.', '')
					));
				}

				$mpgCustInfo->setEmail($transaction->customerEmail);
				$mpgCustInfo->setInstructions($transaction->txnComments);

				foreach($transaction->txnItems as $txnItem)
				{
					$mpgCustInfo->setItems(array(
						'name'=>$txnItem->getDescription(),
						'quantity'=>$txnItem->getQuantity(),
						'product_code'=>$txnItem->getProductCode(),
						'extended_amount'=>number_format($txnItem->getAmount(), 2, '.', '')
					));
				}

				$exp = strtotime( "{$transaction->creditCardExpiryYear}-{$transaction->creditCardExpiryMonth}-01" );

				$mpgTxn = new \mpgTransaction(array(
					'type'=>'purchase',
					'order_id'=>$transaction->txnOrderNo,
					'cust_id'=>$transaction->txnCustomerId,
					'amount'=>number_format($transaction->getTotal(), 2, '.', ''),
					'pan'=>$transaction->creditCardNumber,
					'expdate'=>date( "ym", $exp),
					'crypt_type'=>'7'
				));

				$avs_street_number = substr($transaction->billingAddress, 0, strpos($transaction->billingAddress, " "));
				$avs_street_name = substr($transaction->billingAddress, strpos($transaction->billingAddress, " "));

				$mpgAvsInfo = new \mpgAvsInfo (array(
					 'avs_street_number'=> $avs_street_number,
					 'avs_street_name' => $avs_street_name,
					 'avs_zipcode' => $transaction->billingPostalCode
					));

				$mpgCvdInfo = new \mpgCvdInfo (array(
					 'cvd_indicator' => '1',
					 'cvd_value' => $transaction->creditCardCVV
					));

				$mpgTxn->setCustInfo($mpgCustInfo);
				/// Disabled
				// $mpgTxn->setAvsInfo($mpgAvsInfo);
				// $mpgTxn->setCvdInfo($mpgCvdInfo);
				$mpgRequest = new \mpgRequest($mpgTxn);
				$mpgHttpPost = new \mpgHttpsPost($this->storeId, $this->apiToken, $mpgRequest);
				$mpgResponse = $mpgHttpPost->getMpgResponse();

				$gatewayResponse->setTransactionType($mpgResponse->getTransType());
				$gatewayResponse->setTransactionDate($mpgResponse->getTransDate());
				$gatewayResponse->setTransactionTime($mpgResponse->getTransTime());
				$gatewayResponse->setAmount($mpgResponse->getTransAmount());
				$gatewayResponse->setOrderNo($mpgResponse->getReceiptId());
				$gatewayResponse->setReferenceNo($mpgResponse->getReferenceNum());
				$gatewayResponse->setAuthCode($mpgResponse->getAuthCode());
				$gatewayResponse->setISOCode($mpgResponse->getISO());
				$gatewayResponse->setResponseCode($mpgResponse->getResponseCode());
				$gatewayResponse->setResponseMessage($mpgResponse->getMessage());
				if($mpgResponse->getResponseCode() == 'null')
				{
					$gatewayResponse->setTransactionStatus(TransactionStatus::incomplete());
				}
				elseif($mpgResponse->getResponseCode() < 50)
				{
					$gatewayResponse->setTransactionStatus(TransactionStatus::approved());
				}
				else
				{
					$gatewayResponse->setTransactionStatus(TransactionStatus::declined());
				}
				$gatewayResponse->setAVSResult($mpgResponse->getAvsResultCode());
				$gatewayResponse->setCVVResult($mpgResponse->getCvdResultCode());
				$gatewayResponse->lock();
			}
			else
			{
				throw new InvalidOperationException("Transaction type not supported");
			}

			restore_error_handler();
			return $gatewayResponse;
		}
	}
?>