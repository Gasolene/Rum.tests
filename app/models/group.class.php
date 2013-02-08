<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a Group table withing a database or an instance of a single
	 * record in the Group table and provides database abstraction
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
	class Group extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'group';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= 'group_id';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= array(
			'group_name' => 'string'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= array(
			'group_id' => array('required','numeric','length(0, 10)'),
			'group_name' => array('required','length(0, 765)')
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
			array(
				'relationship' => 'has_many_and_belongs_to',
				'type' => 'MyApp\Models\Customer',
				'table' => 'group_customer',
				'columnRef' => 'customer_id',
				'columnKey' => 'group_id',
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

		static public function dataSet( array $args = array() ) {
			return ActiveRecordBase::dataSet( $args );
		}

		static public function findAll( array $args = array() ) {
			return ActiveRecordBase::findAll( $args );
		}
	}
?>