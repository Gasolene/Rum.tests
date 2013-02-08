<?php
    namespace MyApp\Models;

	class TablePage extends \System\ActiveRecords\ActiveRecordBase
	{
		var $table			= 'page';

		var $pkey			= 'page_id';

		var $prefix			= 'page_';

		var $relationships	= array(
								array(
									  'relationship' => 'has_many'
									, 'type' => 'MyApp\Models\TablePage'
									, 'table' => 'page'
									, 'columnRef' => 'parent_id'
									, 'columnKey' => 'page_id'
									, 'notNull' => true ),
								array(
									  'relationship' => 'belongs_to'
									, 'type' => 'MyApp\Models\TablePage'
									, 'table' => 'page'
									, 'columnRef' => 'parent_id'
									, 'columnKey' => 'page_id'
									, 'notNull' => true )
								);
	}
?>