<?php
    namespace MyApp\Models;
    use \System\ActiveRecords\ActiveRecordBase;


	class Team extends ActiveRecordBase
	{
		static public function find( array $arg ) {
			return ActiveRecordBase::find( $arg );
		}

		static public function add( array $args = array()) {
			return ActiveRecordBase::add( $args );
		}
	}
?>