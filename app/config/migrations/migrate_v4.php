<?php
	/**
	 * Migration script
	 *
	 * Configure this script with your database migration
	 *
	**/

	namespace System\Migrate;

	class Migrate_V4 extends MigrationBase
	{
		public $version = 4;

		public function up()
		{
			$this->db->execute("ALTER TABLE `test_migrations` CHANGE `newfield` `renamefield` INT( 11 ) NOT NULL ;");
		}

		public function down()
		{
			$this->db->execute("ALTER TABLE `test_migrations` CHANGE `renamefield` `newfield` INT( 11 ) NOT NULL ;");
		}
	}
?>