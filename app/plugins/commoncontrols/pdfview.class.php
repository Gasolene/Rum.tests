<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\View;


	/**
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class PDFView extends View
	{
		/**
		 * specify content-Type
		 * @access protected
		 */
		protected $contentType			= 'application/pdf';


		/**
		 * send headers
		 *
		 * @return	void
		 * @access	protected
		 */
		protected function sendHeaders() {
			parent::sendHeaders();

			/* ie bug fix */
			\System\Web\HttpResponse::addHeader("Pragma: public");
			\System\Web\HttpResponse::addHeader("Expires: 0");
			\System\Web\HttpResponse::addHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			\System\Web\HttpResponse::addHeader("Cache-Control: public");
			// \System\Web\HttpResponse::addHeader("Content-Description: File Transfer");
			\System\Web\HttpResponse::flush();
		}
	}
?>