<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace GatewayAdapter;


	/**
	 * Virtual Adapter
	 * 
	 * @author			Darnell Shinbine
	 * @package			GatewayAdapter
	 */
	final class VirtualAdapter implements IGatewayAdapter
	{
		/**
		 * Constructor
		 *
		 * @return	void
		 * @access	public
		 */
		public function __construct() {}


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

			if($transaction->txnType == TransactionType::purchase())
			{
				if($transaction->creditCardExpiryMonth >= date('m') && $transaction->creditCardExpiryYear >= date('y'))
				{
					$gatewayResponse->setTransactionType('purchase');
					$gatewayResponse->setTransactionDate(date('Y-m-d'));
					$gatewayResponse->setTransactionTime(date('h:i:s'));
					$gatewayResponse->setAmount($transaction->getTotal());
					$gatewayResponse->setOrderNo(time());
					$gatewayResponse->setReferenceNo(time());
					$gatewayResponse->setAuthCode('NULL');
					$gatewayResponse->setISOCode('NULL');
					$gatewayResponse->setResponseCode('0');
					$gatewayResponse->setResponseMessage('APPROVED');
					$gatewayResponse->setTransactionStatus(TransactionStatus::approved());
					$gatewayResponse->setAVSResult('YYY');
					$gatewayResponse->setCVVResult('YY');
					$gatewayResponse->lock();
				}
				else
				{
					$gatewayResponse->setTransactionType('purchase');
					$gatewayResponse->setTransactionDate(date('Y-m-d'));
					$gatewayResponse->setTransactionTime(date('h:i:s'));
					$gatewayResponse->setAmount($transaction->getTotal());
					$gatewayResponse->setOrderNo(time());
					$gatewayResponse->setReferenceNo(time());
					$gatewayResponse->setAuthCode('NULL');
					$gatewayResponse->setISOCode('NULL');
					$gatewayResponse->setResponseCode('0');
					$gatewayResponse->setResponseMessage('DECLINED');
					$gatewayResponse->setTransactionStatus(TransactionStatus::declined());
					$gatewayResponse->setAVSResult('YYN');
					$gatewayResponse->setCVVResult('NN');
					$gatewayResponse->lock();
				}
			}
			else
			{
				throw new InvalidOperationException("Transaction type not supported");
			}

			return $gatewayResponse;
		}
	}
?>