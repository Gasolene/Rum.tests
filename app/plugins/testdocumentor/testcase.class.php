<?php

	namespace TestDocumentor;

	class TestCase
	{
		/**
		 * entrance conditions
		 * @var array
		 */
		private $entranceConditions = array();

		/**
		 * entrance fixtures
		 * @var array
		 */
		private $entranceFixtures = '';

		/**
		 * tests
		 * @var array
		 */
		private $tests = array();

		/**
		 * test case name
		 * @var string
		 */
		private $name = '';

		/**
		 * test case description
		 * @var string
		 */
		private $description = '';

		/**
		 * count
		 * @var int
		 */
		static public $count = 0;


		/**
		 * Constructor
		 *
		 * @param string $name name of test
		 * @param string $description description of test
		 * @return void
		 */
		public function __construct($name, $description) {
			$this->name = (string)$name;
			$this->description = (string)$description;
		}


		/**
		 * Add test condition
		 *
		 * @param string $table table name
		 * @param array $rows table rows
		 * @return void
		 */
		public function addPreCondition($table, array $rows = array()) {
			$this->entranceConditions[(string)$table] = $rows;
		}


		/**
		 * Add test fixtures
		 *
		 * @param string $fixtures fixtures
		 * @return void
		 */
		public function addFixtures($fixtures) {
			$this->entranceFixtures = $fixtures;
		}


		/**
		 * Add test
		 *
		 * @param Test $test Test to add
		 * @return void
		 */
		public function addTest(Test $test) {
			$this->tests[] = $test;
			self::$count++;
		}


		/**
		 * Get test case name
		 *
		 * @return string
		 */
		public function getName() {
			return $this->name;
		}


		/**
		 * Get test case description
		 *
		 * @return string
		 */
		public function getDescription() {
			return $this->description;
		}


		/**
		 * Get entrance conditions
		 *
		 * @return array
		 */
		public function getPreConditions() {
			return $this->entranceConditions;
		}


		/**
		 * Get entrance fixtures
		 *
		 * @return string
		 */
		public function getFixtures() {
			return $this->entranceFixtures;
		}


		/**
		 * Get tests
		 *
		 * @return array
		 */
		public function getTests() {
			return $this->tests;
		}
	}
?>