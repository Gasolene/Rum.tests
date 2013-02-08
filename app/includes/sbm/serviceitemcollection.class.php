<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Packages\Commerx\SBM;

	use System\Collections\CollectionBase;
	use System\TypeMismatchException;
	use System\IndexOutOfRangeException;
	use System\InvalidArgumentException;

	/**
	 * Represents a collection of service items
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		Commerx.SBM
	 */
	final class ServiceItemCollection extends CollectionBase
	{
		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetSet($index, $item)
		{
			if( array_key_exists( $index, $this->items ))
			{
				if( $item instanceof ServiceItem )
				{
					return $this->items[$index] = $item;
				}
				else
				{
					throw new TypeMismatchException("invalid index value expected object of class ServiceItem in ".get_class($this));
				}
			}
			else
			{
				throw new IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}

		/**
		 * add
		 *
		 * add a ServiceItem object to the Collection
		 *
		 * @param  ServiceItem $item ServiceItem object
		 *
		 * @return void
		 * @access public
		 */
		public function add( $item )
		{
			if( $item instanceof ServiceItem )
			{
				array_push( $this->items, $item );
			}
			else
			{
				throw new InvalidArgumentException("Argument 1 passed to ".get_class($this)."::add() must be an object of class ServiceItem");
			}
		}
	}
?>