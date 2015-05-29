<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a Student table withing a database or an instance of a single
	 * record in the Student table and provides database abstraction
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
	class Student extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'student';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= 'student_id';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= array(
			'student_name' => 'string',
			'student_age' => 'numeric'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= array(
			'student_id' => array('numeric','length(0, 10)'),
			'student_name' => array('length(0, 240)'),
			'student_age' => array('numeric','length(0, 10)')
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
			array(
				'relationship' => 'has_many_and_belongs_to',
				'type' => 'MyApp\Models\Classrooms',
				'table' => 'student_classrooms',
				'columnRef' => 'classroom_id',
				'columnKey' => 'student_id',
				'notNull' => '1'
			)
		);
	}
?>