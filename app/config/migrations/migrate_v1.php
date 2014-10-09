<?php
	/**
	 * Migration script
	 *
	 * Configure this script with your database migration
	 *
	**/

	namespace System\Migrate;

	class Migrate_v1 extends MigrationBase
	{
		public $version = 0.9;

		public function up()
		{
			$this->db->execute("CREATE TABLE `test_migrations` (`test_migrations` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,`textfield` VARCHAR( 255 ) NOT NULL) ENGINE = InnoDB");
			return $this->db->prepare("INSERT INTO `test_migrations` (`test_migrations` ,`textfield`)VALUES (NULL , 'Blue'), (NULL , 'Orange');");
		}

		public function down()
		{
			return $this->db->prepare("DROP TABLE `test_migrations`;");
		}
	}
?>