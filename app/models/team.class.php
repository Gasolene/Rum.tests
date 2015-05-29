<?php
	/**
	 * @package MyApp\Models
	 */
	namespace MyApp\Models;

	/**
	 * This class represents represents a Team table withing a database or an instance of a single
	 * record in the Team table and provides database abstraction
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
	class Team extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= 'team';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= 'team_id';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= array(
			'player_id' => 'ref'
		);

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= array(
			'team_id' => array('numeric','length(0, 10)'),
			'player_id' => array('numeric','length(0, 10)')
		);

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= array(
			array(
				'relationship' => 'has_many',
				'type' => 'MyApp\Models\Player',
				'table' => 'player',
				'columnRef' => 'team_id',
				'columnKey' => 'team_id',
				'notNull' => '0'
			),
			array(
				'relationship' => 'belongs_to',
				'type' => 'MyApp\Models\Player',
				'table' => 'team',
				'columnRef' => 'player_id',
				'columnKey' => 'player_id',
				'notNull' => '0'
			)
		);
	}
?>