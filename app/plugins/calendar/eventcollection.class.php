<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace Calendar;
	use \System\Collections\StringDictionary;


	/**
	 * Collection of events
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		Calendar
	 */
	final class EventCollection extends StringDictionary
	{
		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetSet($index, $item)
		{
			$timestamp = strtotime( (string)$index );

			if( $timestamp !== false )
			{
				if( is_string( $item ))
				{
					$this->items[date('Y-m-d', $timestamp)] = (string)$item;
				}
				else
				{
					throw new \Exception("invalid index value expected string in ".get_class($this));
				}
			}
			else
			{
				throw new \Exception("invalid key value expected valid datetime string in ".get_class($this));
			}
		}


		/**
		 * Adds a new key/item pair to a StringCollection if key does not already exist
		 *
		 * @param  string		$key			key
		 * @param  string		$value			value
		 * 
		 * @return void
		 * @access public
		 */
		public function add( $key, $value )
		{
			$timestamp = strtotime( (string)$key );

			if( $timestamp !== false )
			{
				if( is_string( $value ))
				{
					return parent::add( date('Y-m-d', $timestamp), $value );
				}
				else
				{
					throw new \Exception("Argument 2 passed to ".get_class($this)."::add() must be a string");
				}
			}
			else
			{
				throw new \Exception("Argument 1 passed to ".get_class($this)."::add() must be a valid datetime string");
			}
		}
	}
?>