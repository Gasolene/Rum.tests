<?php
	/**
	 * Migration script
	 *
	 * Configure this script with your database migration
	 *
	**/

	namespace System\Migrate;

	class Migrate_V2 extends MigrationBase
	{
		public $version = 1.1;

		public function up()
		{
			$this->db->executeBatch("
				ALTER TABLE `test_migrations` CHANGE `textfield` `rename` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
				ALTER TABLE `test_migrations` ADD `newfield` INT NOT NULL AFTER `rename` ;");
		}

		public function down()
		{
			$this->db->executeBatch("
				ALTER TABLE `test_migrations` DROP `newfield`  ;
				ALTER TABLE `test_migrations` CHANGE `rename` `textfield` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL  ;");
		}
	}
?>