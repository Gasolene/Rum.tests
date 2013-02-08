<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;

	use \Beanstream\Beanstream;

	/**
	 * include api
	 */
	require_once __PLUGINS_PATH__ . '/gatewayadapter/api\'s/beanstream/beanstream.php';

	/**
	 * Beanstream Adapter
	 *
	 * @author			Darnell Shinbine
	 * @package			GatewayAdapter
	 */
	final class BeanstreamAdapter implements IGatewayAdapter
	{
		/**
		 * merchant id
		 * @access protected
		 * @var string
		**/
		protected $merchantId		= "";

		/**
		 * username
		 * @access protected
		 * @var string
		**/
		protected $username			= "";

		/**
		 * password
		 * @access protected
		 * @var string
		**/
		protected $password			= "";


		/**
		 * Constructor
		 *
		 * @param	string		$merchantId		Beanstream merchant id
		 * @param	string		$username		Beanstream username
		 * @param	string		$password		Beanstream password
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct($merchantId, $username, $password)
		{
			$this->merchantId = (string)$merchantId;
			$this->username = (string)$username;
			$this->password = (string)$password;
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
			$gatewayResponse = new GatewayResponse();

/*
// Initialize curl
$ch = curl_init();
// Get curl to POST
curl_setopt( $ch, CURLOPT_POST, 1 );
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// Instruct curl to suppress the output from Beanstream, and to directly
// return the transfer instead. (Output will be stored in $txResult.)
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
// This is the location of the Beanstream payment gateway
curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/process_transaction.asp" );
// These are the transaction parameters that we will POST
curl_setopt( $ch, CURLOPT_POSTFIELDS, "requestType=BACKEND&merchant_id=109040000&trnCardOwner=Paul+Randal&trnCardNumber=5100000010001004&trnExpMonth=01&trnExpYear=05&trnOrderNumber=2232&trnAmount=10.00&ordEmailAddress=prandal@mydomain.net&ordName=Paul+Randal&ordPhoneNumber=9999999&ordAddress1=1045+Main+Street&ordAddress2=&ordCity=Vancouver&ordProvince=BC&ordPostalCode=V8R+1J6&ordCountry=CA" );
// Now POST the transaction. $txResult will contain Beanstream's response
$txResult = curl_exec( $ch );
echo "Result:<BR>";
dmp($txResult);
curl_close( $ch );
*/

			$api = new \BeanStream();

			if($transaction->txnType == TransactionType::purchase())
			{
				$api->setMerchantId($this->merchantId);
				$api->setPassword($this->password);
				$api->setUsername($this->username);
				$api->setTransactionAmount($transaction->getTotal());
				$api->setTransactionOrderNumber($transaction->txnOrderNo);
				$api->setTransactionType(BS_PURCHASE);

				$api->setBillingEmail($transaction->customerEmail);
				$api->setBillingPhone($transaction->customerPhoneNumber);
				$api->setBillingName($transaction->customerFirstName . ' ' . $transaction->customerLastName);
				$api->setBillingAddress1($transaction->billingAddress);
				$api->setBillingCity($transaction->billingCity);
				$api->setBillingProvince($transaction->billingProvince);
				$api->setBillingCountry($transaction->billingCountry);
				$api->setBillingPostalCode($transaction->billingPostalCode);

				$api->setCardCVD($transaction->creditCardCVV);
				$api->setCardOwner($transaction->creditCardHolder);
				$api->setCardNumber($transaction->creditCardNumber);
				$api->setExpMonth($transaction->creditCardExpiryMonth);
				$api->setExpYear("20".$transaction->creditCardExpiryYear);

				$api->process();
				$response = $api->getResponse();

				if ( $response['responseType'] == 'T' )
				{
					if ( $response['trnApproved'] == '1' )
					{
						$gatewayResponse->setTransactionType(BS_PURCHASE);
						$gatewayResponse->setTransactionDate(date('Y-m-d'));
						$gatewayResponse->setTransactionTime(date('G:h:i'));
						$gatewayResponse->setAmount($transaction->getTotal());
						$gatewayResponse->setOrderNo($transaction->txnOrderNo);
						$gatewayResponse->setReferenceNo($response["trnId"]);
						$gatewayResponse->setAuthCode($response["trnAuthCode"]);
						$gatewayResponse->setISOCode("");
						$gatewayResponse->setResponseCode($response["messageId"]);
						$gatewayResponse->setResponseMessage($response["messageText"]);
						$gatewayResponse->setTransactionStatus(TransactionStatus::approved());
					}
					else
					{
						$gatewayResponse->setTransactionType(BS_PURCHASE);
						$gatewayResponse->setTransactionDate(date('Y-m-d'));
						$gatewayResponse->setTransactionTime(date('G:h:i'));
						$gatewayResponse->setAmount($transaction->getTotal());
						$gatewayResponse->setOrderNo($transaction->txnOrderNo);
						$gatewayResponse->setReferenceNo($response["trnId"]);
						$gatewayResponse->setAuthCode($response["trnAuthCode"]);
						$gatewayResponse->setISOCode("");
						$gatewayResponse->setResponseCode($response["messageId"]);
						$gatewayResponse->setResponseMessage($response["messageText"]);
						$gatewayResponse->setTransactionStatus(TransactionStatus::declined());
					}
				}
				else
				{
					throw new InvalidOperationException("Response type not supported");
				}

				//$gatewayResponse->setAVSResult();
				//$gatewayResponse->setCVVResult();
				$gatewayResponse->lock();
			}
			else
			{
				throw new InvalidOperationException("Transaction type not supported");
			}

			return $gatewayResponse;
		}
	}
?>