<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;


	/**
	 * Authorize.net Adapter
	 * 
	 * @author			Darnell Shinbine
	 * @package			GatewayAdapter
	 */
	final class AuthorizeDotNetAdapter implements IGatewayAdapter
	{
		/**
		 * login id
		 * @access protected
		 * @var string
		**/
		protected $loginId			= "";

		/**
		 * transaction key
		 * @access protected
		 * @var string
		**/
		protected $transactionKey	= "";

		/**
		 * devMode
		 * @access protected
		 * @var string
		**/
		protected $devMode			= false;

		/**
		 * test_post_url
		 * @var string
		**/
		const test_post_url			= "https://test.authorize.net/gateway/transact.dll";

		/**
		 * post_url
		 * @var string
		**/
		const post_url				= "https://secure.authorize.net/gateway/transact.dll";


		/**
		 * Constructor
		 *
		 * @param	string		$loginId			Authorize.net login id
		 * @param	string		$transactionKey		Authorize.net transaction id
		 * @param	string		$devMode			Speifies whether to enable dev mode (Optional)
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct($loginId, $transactionKey, $devMode = false)
		{
			$this->loginId = (string)$loginId;
			$this->transactionKey = (string)$transactionKey;
			$this->devMode = (bool)$devMode;
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

			// By default, this sample code is designed to post to our test server for
			// developer accounts: https://test.authorize.net/gateway/transact.dll
			// for real accounts (even in test mode), please make sure that you are
			// posting to: https://secure.authorize.net/gateway/transact.dll
			$post_url = $this->devMode?self::test_post_url:self::post_url;

			if($transaction->txnType == TransactionType::purchase())
			{
				$post_values = array(

					// the API Login ID and Transaction Key must be replaced with valid values
					"x_login"			=> $this->loginId,
					"x_tran_key"		=> $this->transactionKey,

					"x_version"			=> "3.1",
					"x_delim_data"		=> "TRUE",
					"x_delim_char"		=> "|",
					"x_relay_response"	=> "FALSE",

					"x_type"			=> "AUTH_CAPTURE",
					"x_method"			=> "CC",
					"x_card_num"		=> $transaction->creditCardNumber,
					"x_exp_date"		=> $transaction->creditCardExpiryMonth . $transaction->creditCardExpiryYear,

					"x_amount"			=> $transaction->getTotal(),
					"x_description"		=> "",

					"x_first_name"		=> $transaction->customerFirstName,
					"x_last_name"		=> $transaction->customerLastName,
					"x_address"			=> $transaction->billingAddress,
					"x_state"			=> $transaction->billingProvince,
					"x_zip"				=> $transaction->billingPostalCode
					// Additional fields can be added here as outlined in the AIM integration
					// guide at: http://developer.authorize.net
				);

				// This section takes the input fields and converts them to the proper format
				// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
				$post_string = "";
				foreach( $post_values as $key => $value )
					{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
				$post_string = rtrim( $post_string, "& " );

				// This sample code uses the CURL library for php to establish a connection,
				// submit the post, and record the response.
				// If you receive an error, you may want to ensure that you have the curl
				// library enabled in your php configuration
				$request = curl_init($post_url); // initiate curl object
					curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
					curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
					curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
					curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
					$post_response = curl_exec($request); // execute curl post and store results in $post_response
					// additional options may be required depending upon your server configuration
					// you can find documentation on curl options at http://www.php.net/curl_setopt
				curl_close ($request); // close curl object

				// This line takes the response and breaks it into an array using the specified delimiting character
				$response = explode($post_values["x_delim_char"],$post_response);

				// individual elements of the array could be accessed to read certain response
				// fields.  For example, response_array[0] would return the Response Code,
				// response_array[2] would return the Response Reason Code.
				// for a list of response fields, please review the AIM Implementation Guide

				$gatewayResponse->setTransactionType($response[11]);
				$gatewayResponse->setTransactionDate(date('Y-m-d'));
				$gatewayResponse->setTransactionTime(date('G:h:i'));
				$gatewayResponse->setAmount($response[9]);
				$gatewayResponse->setOrderNo($response[7]);
				$gatewayResponse->setReferenceNo(6);
				$gatewayResponse->setAuthCode($response[4]);
				//$gatewayResponse->setISOCode("");
				$gatewayResponse->setResponseCode($response[1]);
				$gatewayResponse->setResponseMessage($response[3]);
				$gatewayResponse->setTransactionStatus($response[0]==1?TransactionStatus::approved():TransactionStatus::declined());
				$gatewayResponse->setCVVResult($response[38]);
				$gatewayResponse->setAVSResult($response[39]);
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