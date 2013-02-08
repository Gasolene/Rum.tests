<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a Nopk table withing a database or an instance of a single
	 * record in the Nopk table and provides database abstraction
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
	class NoPk extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'nopk';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= '';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= array(
			'color' => 'string',
			'size' => 'numeric'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= array(
			'color' => array('required','length(0, 150)'),
			'size' => array('required','numeric','length(0, 11)')
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
		);
	}
?>