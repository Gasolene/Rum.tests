<?php

	define( 'BS_PURCHASE', 'P' );
	define( 'BS_RETURN', 'R' );
	define( 'BS_VOID_PURCHASE', 'VP' );
	define( 'BS_VOID_RETURN', 'VR' );
	define( 'BS_PREAUTH', 'PA' );
	define( 'BS_PREAUTH_COMP', 'PAC' );

	$_BS_COUNTRIES = array(
		'AF','AL','DZ','AS','AD','AO','AI','AQ','AG','AR',
		'AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY',
		'BE','BZ','BJ','BM','BT','BO','BA','BW','BV','BR',
		'IO','BN','BG','BF','BI','KH','CM','CA','CV','KY',
		'CF','TD','CL','CN','CX','CC','CO','KM','CG','CD',
		'CK','CR','CI','HR','CU','CY','CZ','DK','DJ','DM',
		'DO','TP','EC','EG','SV','GQ','ER','EE','ET','FK',
		'FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE',
		'DE','DE','GH','GI','GR','GL','GD','GP','GU','GT',
		'GN','GW','GY','HT','HM','HN','HK','HU','IS','IN',
		'ID','IR','IQ','IE','IL','IT','JM','JP','JO','KZ',
		'KE','KI','KP','KR','KW','KG','LA','LV','LB','LS',
		'LR','LY','LI','LT','LU','MO','MK','MG','MW','MY',
		'MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM',
		'MD','MC','MN','MS','MA','MZ','MM','NA','NR','NP',
		'NL','AN','NC','NZ','NI','NE','NG','NU','NF','MP',
		'NO','OM','OM','PK','PW','PS','PA','PG','PY','PE',
		'PH','PN','PL','PT','PR','QA','RE','RO','RU','RW',
		'KN','LC','VC','WS','SM','ST','SA','SN','SC','SL',
		'SG','SK','SI','SB','SO','ZA','GS','ES','LK','SH',
		'PM','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ',
		'TZ','TH','TG','TK','TO','TT','TN','TR','TM','TC',
		'TV','UG','UA','AE','GB','US','UM','UY','UZ','VU',
		'VA','VE','VN','VG','VI','WF','EH','YE','YU','ZM',
		'ZW'
	);

	class BeanStream
	{
		private $soap_client;
		private $request		= array();
		private $response		= array();

		function BeanStream() {
		}

		// Send the request to Beanstream
		public function process()
		{
			$soap_options = array( 'soap_version' => SOAP_1_1, 'trace' => 1 );

			$this->soap_client = new SoapClient( 'http://www.beanstream.com/soap/ProcessTransaction.wsdl', $soap_options );

			if ( !$this->verify() ) {
				trigger_error( 'SOAP Function does not exist in Beanstream WSDL!', E_USER_WARNING );
				return FALSE;
			}

			// Create XML request
			$request_xml  = "<transaction>\n";
			$request_xml .= "\t<serviceVersion>1.2</serviceVersion>\n";
			foreach ( $this->request as $key => $value )
			{
				$request_xml .= "\t<$key>$value</$key>\n";
			}
			$request_xml .= '</transaction>';

			$fp = fopen( __LOG_PATH__ . '/' . time() . 'request_xml.bin', 'w' ); fwrite( $fp, $request_xml ); fclose( $fp );

			$response = $this->soap_client->TransactionProcess( $request_xml );

			$fp = fopen( __LOG_PATH__ . '/' . time() . 'request_soap.bin', 'w' ); fwrite( $fp, $this->soap_client->__getLastRequestHeaders() . "\n\n" ); fwrite( $fp, $this->soap_client->__getLastRequest() ); fclose( $fp );
			$fp = fopen( __LOG_PATH__ . '/' . time() . 'response_soap.bin', 'w' ); fwrite( $fp, $this->soap_client->__getLastResponseHeaders() . "\n\n" ); fwrite( $fp, $this->soap_client->__getLastResponse() ); fclose( $fp );
			$fp = fopen( __LOG_PATH__ . '/' . time() . 'response_xml.bin', 'w' ); fwrite( $fp, $response ); fclose( $fp );

			if ( strlen( $response )) {
				$this->parseResponse( $response );
				return TRUE;
			}
			else {
				trigger_error( 'no response', E_USER_WARNING );
			}

			return FALSE;
		}

		// Verifies that the proper function exists in Beanstreams SOAP wsdl.
		private function verify()
		{
			$functions = implode( ' ', $this->soap_client->__getFunctions() );

			if ( preg_match( '/string TransactionProcess\(string \$/', $functions ) &&
				 preg_match( '/string TransactionProcessAuth\(string \$/', $functions ) )
				return TRUE;

			return FALSE;
		}

		/* RESPONSE PROCESSING */
		private function parseResponse( $xml_response )
		{
			$domDocument = \DOMDocument::loadXML( $xml_response );

			$response = array();

			$response['responseType'] = $domDocument->getElementsByTagName( 'responseType' )->item( 0 )->textContent;

			if ( $response['responseType'] == 'T' )
			{
				$response['trnId'] = $domDocument->getElementsByTagName( 'trnId' )->item( 0 )->textContent;
				$response['trnApproved'] = $domDocument->getElementsByTagName( 'trnApproved' )->item( 0 )->textContent;
				$response['messageId'] = $domDocument->getElementsByTagName( 'messageId' )->item( 0 )->textContent;
				$response['messageText'] = urldecode( $domDocument->getElementsByTagName( 'messageText' )->item( 0 )->textContent );
				$response['trnAuthCode'] = $domDocument->getElementsByTagName( 'trnAuthCode' )->item( 0 )->textContent;
				$response['errorType'] = $domDocument->getElementsByTagName( 'errorType' )->item( 0 )->textContent;
				$response['errorFields'] = $domDocument->getElementsByTagName( 'errorFields' )->item( 0 )->textContent;
			}
			else if ( $response['responseType'] == 'R' )
			{
				$response['redirectionPage'] = $domDocument->getElementsByTagName( 'redirectionPage' )->item( 0 )->textContent;
			}

			$this->response = $response;
		}

		public function getResponse()
		{
			return $this->response;
		}

		/* VERIFIED BY VISA */
		public function processVBV( $md, $pares )
		{
			$fp = fopen( "https://www.beanstream.com/scripts/process_transaction_auth.asp?md=$md&paRes=$pares", 'r' );
			$response = @stream_get_contents( $fp );
			fclose( $fp );

			$fp = fopen( __LOG_PATH__ . '/' . time() . 'response_vbv.bin', 'w' ); fwrite( $fp, $response ); fclose( $fp );

			// Parse response
			$array = array();
			parse_str( $response, $array );

			return $array;
		}

		/* GENERAL INFORMATION */
		public function setMerchantId( $merchant_id )
		{
			if ( !preg_match( '/^\d{9}$/', $merchant_id ) )
				trigger_error( 'Merchant ID must be 9 digits', E_USER_NOTICE );

			$this->request['merchant_id'] = $merchant_id;
		}

		public function setTransactionOrderNumber( $order_id )
		{
			if ( !preg_match( '/^(\w|-){1,30}?$/', $order_id ) )
				trigger_error( 'Order number must be between 1 and 30 characters composed of alpha-numeric.', E_USER_NOTICE );

			$this->request['trnOrderNumber'] = $order_id;
		}

		public function setTransactionType( $type )
		{
			if ( $type != BS_PURCHASE &&
				 $type != BS_RETURN &&
				 $type != BS_VOID_PURCHASE &&
				 $type != BS_VOID_RETURN &&
				 $type != BS_PREAUTH &&
				 $type != BS_PREAUTH_COMP )
				trigger_error( 'Invalid transaction type', E_USER_NOTICE );

			$this->request['trnType'] = $type;
		}

		public function setTransactionAmount( $amount )
		{
			if ( !preg_match( '/^(\d{1,10}?|\d{1,7}\.\d{2}|\d{1,8}\.\d{1})$/', $amount ) )
				trigger_error( 'Invalid transaction amount', E_USER_NOTICE );

			$this->request['trnAmount'] = $amount;
		}

		public function setAdjustmentId( $id )
		{
			if ( !preg_match( '/^\d{8}$/', $id ) )
				trigger_error( 'Invalid adjustment id', E_USER_NOTICE );

			$this->request['adjId'] = $id;
		}

		public function setUsername( $username )
		{
			if ( !preg_match( '/^\w{1,16}$/', $username ) )
				trigger_error( 'Invalid username', E_USER_NOTICE );

			$this->request['username'] = $username;
		}

		public function setPassword( $password )
		{
			if ( !preg_match( '/^\w{1,16}$/', $password ) )
				trigger_error( 'Invalid password', E_USER_NOTICE );

			$this->request['password'] = $password;
		}

		public function setVBVEnabled( $boolean )
		{
			if ( !is_bool( $boolean ) )
				trigger_error( 'Invalid boolean', E_USER_NOTICE );

			$this->request['vbvEnabled'] = intval( $boolean );
		}

		public function setSCEnabled( $boolean )
		{
			if ( !is_bool( $boolean ) )
				trigger_error( 'Invalid boolean', E_USER_NOTICE );

			$this->request['scEnabled'] = intval( $boolean );
		}

		public function setTermUrl( $url )
		{
			$this->request['termUrl'] = $url;
		}

		/* CREDIT INFOMATION */

		public function setCardOwner( $owner )
		{
			if ( !preg_match( '/^(\w|\s){4,32}$/', $owner ) )
				trigger_error( 'Invalid card owner', E_USER_NOTICE );

			$this->request['trnCardOwner'] = $owner;
		}

		public function setCardNumber( $number )
		{
			if ( !preg_match( '/^\d{13,20}$/', $number ) )
				trigger_error( 'Invalid card number', E_USER_NOTICE );

			$this->request['trnCardNumber'] = $number;
		}

		public function setExpMonth( $month )
		{
			if ( !is_numeric( $month ) || $month < 1 || $month > 12 )
				trigger_error( 'Invalid expiry month', E_USER_NOTICE );

			$this->request['trnExpMonth'] = str_pad( $month, 2, 0, STR_PAD_LEFT );
		}

		public function setExpYear( $year )
		{
			if ( !preg_match( '/^\d{4}$/', $year ) || $year < gmdate( 'Y' ) )
				trigger_error( 'Invalid expiry year', E_USER_NOTICE );

			$this->request['trnExpYear'] = substr( $year, 2 );
		}

		public function setCardCVD( $cvd )
		{
			if ( !preg_match( '/^\d{3,4}$/', $cvd ) )
				trigger_error( 'Invalid card CVD', E_USER_NOTICE );

			$this->request['trnCardCvd'] = $cvd;
		}

		/* BILLING INFORMATION */
		public function setBillingName( $name )
		{
//			if ( !preg_match( '/^(\w|\s){1,64}$/', $name ) )
//				trigger_error( 'Invalid billing name', E_USER_NOTICE );

			$this->request['ordName'] = $name;
		}

		public function setBillingEmail( $email )
		{
			if ( !preg_match( '/^.*@.*$/', $email ) )
				trigger_error( 'Invalid billing email', E_USER_NOTICE );

			$this->request['ordEmailAddress'] = $email;
		}

		public function setBillingAddress1( $address1 )
		{
//			if ( !preg_match( '/^(\w|\s){1,32}$/', $address1 ) )
//				trigger_error( 'Invalid billing address', E_USER_NOTICE );

			$this->request['ordAddress1'] = $address1;
		}

		public function setBillingAddress2( $address2 )
		{
//			if ( !preg_match( '/^(\w|\s){1,32}$/', $address2 ) )
//				trigger_error( 'Invalid billing address 2', E_USER_NOTICE );

			$this->request['ordAddress2'] = $address2;
		}

		public function setBillingPhone( $phone )
		{
			if ( !preg_match( '/^(\d|\w|\s|-){7,32}$/', $phone ) )
				trigger_error( 'Invalid phone number', E_USER_NOTICE );

			$this->request['ordPhoneNumber'] = $phone;
		}

		public function setBillingCity( $city )
		{
//			if ( !preg_match( '/^(\d|\w|\s|-){1,32}$/', $city ) )
//				trigger_error( 'Invalid city', E_USER_NOTICE );

			$this->request['ordCity'] = $city;
		}

		public function setBillingProvince( $province )
		{
			if ( !preg_match( '/^(\w|-){2}$/', $province ) )
				trigger_error( 'Invalid province', E_USER_NOTICE );

			$this->request['ordProvince'] = $province;
		}

		public function setBillingCountry( $country )
		{
			if ( !preg_match( '/^\w{2}$/', $country ) )
				trigger_error( 'Invalid country', E_USER_NOTICE );

			$this->request['ordCountry'] = $country;
		}

		public function setBillingPostalCode( $postal )
		{
			if ( !preg_match( '/^(\w|\d|\s|-){1,16}$/', $postal ) )
				trigger_error( 'Invalid postal code', E_USER_NOTICE );

			$this->request['ordPostalCode'] = $postal;
		}

		/* RECURRING BILLING */
		public function setRecurring( $period, $increment, $date = null )
		{
			$this->request['trnRecurring'] = 1;
			$this->request['rbBillingPeriod'] = $period;
			$this->request['rbBillingIncrement'] = $increment;
			if( $date ) {
				$this->request['rbExpiry'] = $date;
			}
		}

		public function setFirstBilling( $date )
		{
			$this->request['rbFirstBilling'] = $date;
		}

		public function setSecondBilling( $date )
		{
			$this->request['rbSecondBilling'] = $date;
		}

		public function setRbCharge( $bool )
		{
			$this->request['rbCharge'] = intval( $bool );
		}

		public static function supportsCountry( $country )
		{
			global $_BS_COUNTRIES;
			return in_array( strtoupper( $country ), $_BS_COUNTRIES );
		}
	}
?>