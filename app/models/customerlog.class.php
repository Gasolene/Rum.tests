<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a CustomerLog table withing a database or an instance of a single
	 * record in the CustomerLog table and provides database abstraction
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
	class CustomerLog extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'customerlog';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= 'customerlog_id';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= array(
			'customerlog_id' => 'numeric',
			'customer_id' => 'ref'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= array(
			'customerlog_id' => array('required','numeric','length(0, 10)'),
			'customer_id' => array('required','numeric','length(0, 10)')
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
			array(
				'relationship' => 'belongs_to',
				'type' => 'MyApp\Models\Customer',
				'table' => 'customerlog',
				'columnRef' => 'customer_id',
				'columnKey' => 'customer_id',
				'notNull' => '1'
			)
		);

		static public function find( array $arg ) {
			return ActiveRecordBase::find( $arg );
		}

		static public function findById( $id ) {
			return ActiveRecordBase::findById( $id );
		}

		static public function add( array $args = array() ) {
			return ActiveRecordBase::add( $args );
		}

		static public function findAll( array $args = array() ) {
			return ActiveRecordBase::findAll( $args );
		}
	}
?>