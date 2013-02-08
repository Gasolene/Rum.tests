<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;


	/**
	 * Gateway Adapter
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	interface IGatewayAdapter
	{
		/**
		 * process transaction
		 *
		 * @param	Transaction		$transaction	Transaction object
		 *
		 * @return	GatewayResponse
		 * @access	public
		 */
		public function process(Transaction &$transaction);
	}
?>