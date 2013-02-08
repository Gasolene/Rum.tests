<?php
    namespace MyApp\Models;
    use \System\ActiveRecords\ActiveRecordBase;


	class School extends ActiveRecordBase
	{
		static public function find( array $arg ) {
			return ActiveRecordBase::find( $arg );
		}
	}
?>