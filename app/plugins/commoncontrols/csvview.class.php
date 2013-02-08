<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\View;


	/**
	 * Represents a CSV View
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class CSVView extends View
	{
		/**
		 * specify content-Type
		 * @access public
		 */
		protected $contentType			= 'text/csv';

		/**
		 * specify attachment name
		 * @access public
		 */
		protected $name					= '';


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field == 'name' ) {
				return $this->name;
			}
			else {
				return parent::__get( $field );
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * 
		 * @return mixed
		 * @access protected
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field == 'name' ) {
				$this->name = (string)$value;
			}
			else {
				parent::__set($field,$value);
			}
		}


		/**
		 * send headers
		 *
		 * @return	void
		 * @access	protected
		 */
		protected function sendHeaders() {
			parent::sendHeaders();

			if( !$this->name ) {
				$this->name = $this->getHTMLControlIdString() . '.csv';
			}

			\System\Web\HTTPResponse::addHeader( "Content-disposition: attachment; filename={$this->name};creation-date=".(date('Y-m-d')).";modification-date=".(date('Y-m-d')).";" );
			\System\Web\HTTPResponse::flush();
		}
	}
?>