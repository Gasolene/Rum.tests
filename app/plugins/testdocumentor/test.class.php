<?php

	namespace TestDocumentor;

	class Test
	{
		/**
		 * test flows
		 * @var array
		 */
		private $flows = array();

		/**
		 * test values
		 * @var array
		 */
		private $results = array();

		/**
		 * test description
		 * @var string
		 */
		private $description = '';

		/**
		 * authenticated user
		 * @var string
		 */
		private $authenticatedUser = '';

		/**
		 * count
		 * @var int
		 */
		static public $count = 0;


		/**
		 * Constructor
		 *
		 * @param	string	$description	description of test flow
		 * @return	void
		 */
		public function __construct($description) {
			$this->description = (string)$description;
		}


		/**
		 * Add test flow
		 *
		 * @param string $description description of test flow
		 * @param array $value array of values
		 * @return void
		 */
		public function addTestFlow($description, array $values = array()) {
			//$testFlow = new TestFlow($description);
			//foreach($values as $name => $value) {
				//$testFlow->addValue($name, $value);
			//}

			$this->authenticatedUser = \System\Security\Authentication::$identity;
			$this->flows[(string)$description] = $values;
		}


		/**
		 * Add test result
		 *
		 * @param string $description description of test flow
		 * @param bool $pass specifies whether test passed or not
		 * @return void
		 */
		public function addTestResult($description, $pass = false) {
			$this->results[] = array((string)$description, (bool)$pass);
			self::$count++;
		}


		/**
		 * Get description
		 *
		 * @return string
		 */
		public function getDescription() {
			return $this->description;
		}


		/**
		 * Get authenticated user
		 *
		 * @return string
		 */
		public function getAuthenticatedUser() {
			return $this->authenticatedUser;
		}


		/**
		 * Get test flows
		 *
		 * @return array
		 */
		public function getTestFlows() {
			return $this->flows;
		}


		/**
		 * Get test results
		 *
		 * @return array
		 */
		public function getTestResults() {
			return $this->results;
		}
	}
?>