<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Packages\Commerx\SBM;

	use System\InvalidOperationException;

	/**
	 * Represents a service item
	 *
	 * @property-read string $code item code
	 * @property-read string $description item description
	 * @property-read int $qty item quantity
	 * @property-read float $price item price
	 * @property-read bool $oneTime specifies whether item is a one time item
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		Commerx.SBM
	 */
	final class ServiceItem
	{
		/**
		 * item code
		 * @access protected
		 * @var string
		**/
		protected $code = "";

		/**
		 * item description
		 * @access protected
		 * @var string
		**/
		protected $description = "";

		/**
		 * item qty
		 * @access protected
		 * @var int
		**/
		protected $qty = 0;

		/**
		 * item price
		 * @access protected
		 * @var float
		**/
		protected $price = 0;

		/**
		 * specifies whether item is a one time item
		 * @access protected
		 * @var bool
		**/
		protected $oneTime = false;


		/**
		 * ServiceItem
		 *
		 * @param	string	$code			Item code
		 * @param	string	$description	Item description
		 * @param	int		$qty			Item quantity
		 * @param	float	$price			Item price
		 * @param	bool	$oneTime		Specifies whether item is a one time item
		 *
		 * @return	void
		 * @access	public
		 */
		public function ServiceItem($code, $description, $qty, $price, $oneTime = false)
		{
			$this->code = (string)$code;
			$this->description = (string)$description;
			$this->qty = (int)$qty;
			$this->price = (real)$price;
			$this->oneTime = (bool)$oneTime;
		}


		/**
		 * __get
		 * 
		 * @param string $field
		 * @return string
		 * @ignore
		 */
		public function __get($field)
		{
			if(in_array($field,array_keys(get_object_vars($this))))
			{
				return $this->{$field};
			}
			else
			{
				throw new BadMemberCallException("property $field does not exist in ".get_class($this));
			}
		}
	}
?>