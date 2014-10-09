<?php
	/**
	 * Migration script
	 *
	 * Configure this script with your database migration
	 *
	**/

	namespace System\Migrate;

	class Migrate_V3 extends MigrationBase
	{
		public $version = 3;

		public function up()
		{
			$this->db->execute("UPDATE `test_migrations` SET `rename` = 'Green',`newfield` = '1' WHERE `test_migrations`.`rename` ='Blue' LIMIT 1 ;");
			//$this->db->execute("UPDATE `doesnotexist` SET `rename` = 'Green',`newfield` = '1' WHERE `test_migrations`.`rename` ='Blue' LIMIT 1 ;");
			return $this->db->prepare("UPDATE `test_migrations` SET `rename` = 'Yellow',`newfield` = '1' WHERE `test_migrations`.`rename` ='Orange' LIMIT 1 ;");
		}

		public function down()
		{
			return $this->db->prepare("UPDATE `test_migrations` SET `rename` = 'Yellow',`newfield` = '1' WHERE `test_migrations`.`rename` ='Orange' LIMIT 1 ;");
		}
	}
?>