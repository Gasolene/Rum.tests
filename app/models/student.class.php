<?php
    namespace MyApp\Models;
    use \System\ActiveRecords\ActiveRecordBase;

	class Student extends ActiveRecordBase
	{
		static public function find( array $arg ) {
			return ActiveRecordBase::find( $arg );
		}

		static public function dataSet() {
			return ActiveRecordBase::dataSet();
		}
	}
?>