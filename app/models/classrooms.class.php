<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a Classrooms table withing a database or an instance of a single
	 * record in the Classrooms table and provides database abstraction
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
	class Classrooms extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'classrooms';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= 'classroom_id';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= array(
			'School_id' => 'ref',
			'name' => 'string'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= array(
			'classroom_id' => array('numeric','length(0, 10)'),
			'School_id' => array('numeric','length(0, 11)'),
			'name' => array('length(0, 240)')
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
			array(
				'relationship' => 'belongs_to',
				'type' => 'MyApp\Models\School',
				'table' => 'classrooms',
				'columnRef' => 'School_id',
				'columnKey' => 'School_id',
				'notNull' => '0'
			),
			array(
				'relationship' => 'has_many_and_belongs_to',
				'type' => 'MyApp\Models\Student',
				'table' => 'student_classrooms',
				'columnRef' => 'student_id',
				'columnKey' => 'classroom_id',
				'notNull' => '1'
			)
		);
	}
?>