<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace ScheduleView;
	use \System\Data\DataAdapter;


	/**
	 * Represents a ScheduleView DataAdapter
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		ScheduleView
	 */
	class ScheduleViewDataAdapter extends DataAdapter
	{
		/**
		 * fetches DataSet from datasource string using source string
		 *
		 * @param  DataSet	&$ds			empty DataSet object
		 * 
		 * @return void
		 * @access public
		 */
		public function fill( \System\DB\DataSet &$ds )
		{
			$ds->setFields(array( 'starttime', 'endtime', 'display' ));
		}


		/**
		 * open
		 * 
		 * @return bool						TRUE if successfull
		 * @access public
		 * @ignore
		 */
		public function open()
		{
			return false;
		}


		/**
		 * close
		 * 
		 * @return bool					true if successfull
		 * @access public
		 * @ignore
		 */
		public function close()
		{
			return false;
		}


		/**
		 * insert data record
		 *
		 * @param  DataSet	$ds		reference to a DataSet
		 * 
		 * @return void
		 * @access public
		 * @ignore
		 */
		public function insert( \System\Data\DataRecord &$dr )
		{
			throw new \System\MethodNotImplementedException();
		}


		/**
		 * updateDataRecord
		 *
		 * @param  DataSet	&$ds		reference to a DataSet
		 * 
		 * @return void
		 * @access public
		 * @ignore
		 */
		public function update( \System\Data\DataRecord &$dr )
		{
			throw new \System\MethodNotImplementedException();
		}


		/**
		 * deleteDataRecord
		 *
		 * @param  DataSet	&$ds		reference to a DataSet
		 * 
		 * @return void
		 * @access public
		 * @ignore
		 */
		public function delete( \System\Data\DataRecord &$dr )
		{
			throw new \System\MethodNotImplementedException();
		}
	}
?>