<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace GatewayAdapter;

	use System\Collections\CollectionBase;
	use System\TypeMismatchException;
	use System\IndexOutOfRangeException;
	use System\InvalidArgumentException;

	/**
	 * Collection of TransactionTax objects
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		GatewayAdapter
	 */
	final class TransactionTaxCollection extends CollectionBase
	{
		/**
		 * implement ArrayAccess methods
		 * @ignore 
		 */
		public function offsetSet($index, $item)
		{
			if( array_key_exists( $index, $this->items ))
			{
				if( $item instanceof TransactionTax )
				{
					$this->items[$index] = $item;
				}
				else
				{
					throw new TypeMismatchException("invalid index value expected object of class TransactionTax in ".get_class($this));
				}
			}
			else
			{
				throw new IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}


		/**
		 * add DataField to Collection
		 *
		 * @param  DataField $item
		 * 
		 * @return bool
		 * @access public
		 */
		public function add( $item )
		{
			if( $item instanceof TransactionTax )
			{
				array_push( $this->items, $item );
			}
			else
			{
				throw new InvalidArgumentException("Argument 1 passed to ".get_class($this)."::add() must be an object of class TransactionTax");
			}
		}


		/**
		 * returns true if array item is found
		 *
		 * @param  string		$tax_name		name of tax
		 * 
		 * @return bool
		 * @access public
		 */
		public function contains( $tax_name )
		{
			for( $i = 0, $count = count( $this->items ); $i < $count; $i++ )
			{
				if( $this->items[$i]->getName() === $tax_name )
				{
					return true;
				}
			}
			return false;
		}


		/**
		 * returns index if value is found in collection
		 *
		 * @param  string		$tax_name		name of tax
		 * 
		 * @return int
		 * @access public
		 */
		public function indexOf( $tax_name )
		{
			for( $i = 0, $count = count( $this->items ); $i < $count; $i++ )
			{
				if( $this->items[$i]->getName() === $tax_name )
				{
					return $i;
				}
			}
			return -1;
		}
	}
?>