<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a Customer table withing a database or an instance of a single
	 * record in the Customer table and provides database abstraction
	 *
	 * The ActiveRecordBase exposes 5 protected properties, do not define these properties in the sub class
	 * to have the properties auto determined
	 * 
	 * @property string $table Specifies the table name
	 * @property string $pkey Specifies the primary key (there can only be one primary key defined)
	 * @property array $fields Specifies field names mapped to field types
	 * @property array $rules Specifies field names mapped to field rules
	 * @property array $relationahips Specifies table relationships
	 *
	 * @package			MyApp\Models
	 */
	class Customer extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'customer';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= 'customer_id';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields = array(
			'category_id' => 'ref',
			'customer_name' => 'string',
			'customer_phone' => 'string',
			'customer_birthday' => 'date',
			'customer_active' => 'boolean'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules = array(
			'customer_name' => array('required', 'length(3,5)'),
			'customer_birthday' => 'datetime'
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
			array(
				'relationship' => 'belongs_to',
				'type' => 'MyApp\Models\Category',
				'table' => 'customer',
				'columnRef' => 'category_id',
				'columnKey' => 'category_id',
				'notNull' => '1'
			),
			array(
				'relationship' => 'has_many',
				'type' => 'MyApp\Models\Customerlog',
				'table' => 'customerlog',
				'columnRef' => 'customer_id',
				'columnKey' => 'customer_id',
				'notNull' => '1'
			),
			array(
				'relationship' => 'has_many_and_belongs_to',
				'type' => 'MyApp\Models\Group',
				'table' => 'group_customer',
				'columnRef' => 'group_id',
				'columnKey' => 'customer_id',
				'notNull' => '1'
			)
		);

		protected function afterCreate()
		{
			$this["customer_name"] = 'default';
			$this["customer_active"] = true;
			$this["customer_details"] = '';
		}
	}
?>