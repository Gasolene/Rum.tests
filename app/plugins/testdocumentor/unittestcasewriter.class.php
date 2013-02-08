<?php // all unit tests should inherit this class

	namespace TestDocumentor;

	abstract class UnitTestCaseWriter extends \System\Testcase\UnitTestCaseBase
	{
		/**
		 * test documentor
		 * @var TestDocumentor
		 */
		static private $testDocumentor = null;

		/**
		 * test case
		 * @var TestCase
		 */
		private $testCase = null;

		/**
		 * test
		 * @var Test
		 */
		static private $test = null;


		/**
		 * Constructor
		 *
		 * @param  string $testCase name of test case
		 * @return void
		 */
		public function __construct($testCase = '') {
			if(!self::$testDocumentor) self::$testDocumentor = new TestDocumentor ();
			parent::__construct($testCase);

			$this->testCase = new TestCase($this->_label, "This test case will test individual components of the {$testCase} software object");
		}


		/**
		 * Destructor
		 *
		 * @return void
		 */
		public function __destruct() {
			self::$testDocumentor->documentTestCase($this->testCase);
		}


		/**
		 * setup test module
		 *
		 * @return  void
		 */
		public function setUp() {
			parent::setUp();

			if(isset($this->_reporter->_test_stack[2]))
			{
				self::$test = new Test($this->_reporter->_test_stack[2].'()');
			}
			else
			{
				self::$test = new Test($this->_reporter->_test_stack[1].'()');
			}

			self::$test->addTestFlow('Unit Test (This test cannot be replicated by a human)');
		}


		/**
		 * clean test module
		 *
		 * @return  void
		 */
		public function tearDown() {
			parent::tearDown();

			$this->testCase->addTest(self::$test);
		}


		/**
		 * load fixtures
		 *
		 * @param   array		$fixtures		array of fixtures
		 * @return  void
		 */
		final protected function loadFixtures( $fixtures )
		{
			parent::loadFixtures($fixtures);

			// add pre conditions
			foreach(\System\Web\WebApplicationBase::getInstance()->dataAdapter->getTables()->rows as $row)
			{
				$table = array_values($row);
				$this->testCase->addPreCondition($table[0], \System\Web\WebApplicationBase::getInstance()->dataAdapter->openDataSet($table[0])->rows);
			}

			$this->testCase->addFixtures($fixtures);
		}

		/**
		 *
		 * @param <type> $first
		 * @param <type> $second
		 * @param <type> $message
		 */
		final public function assertEqual($first, $second, $message)
		{
			$retval = parent::assertEqual($first, $second, $message);
			self::$test->addTestResult($message, $retval);
			return $retval;
		}

		/**
		 *
		 * @param <type> $value
		 * @param <type> $message
		 */
		final public function assertNull($value, $message)
		{
			$retval = parent::assertNull($value, $message);
			return $retval;
		}

		/**
		 *
		 * @param <type> $value
		 * @param <type> $message
		 */
		final public function assertNotNull($value, $message)
		{
			$retval = parent::assertNotNull($value, $message);
			return $retval;
		}

		/**
		 *
		 * @param <type> $result
		 * @param <type> $message
		 * @return <type>
		 */
		final public function assertTrue($result, $message)
		{
			$retval = parent::assertTrue($result, $message);
			self::$test->addTestResult($message, $retval);
			return $retval;
		}

		/**
		 *
		 * @param <type> $result
		 * @param <type> $message
		 */
		final public function assertFalse($result, $message)
		{
			$retval = parent::assertFalse($result, $message);
			self::$test->addTestResult($message, $retval);
			return $retval;
		}


		/**
		 * assert DataSet field
		 *
		 * @param <type> $ds
		 * @param <type> $row
		 * @param <type> $field
		 * @param <type> $value
		 */
		protected function assertDataSetField(\System\Data\DataSet $ds, $row, $field, $value)
		{
			$this->assertEqual($ds->rows[$row][$field], $value, "Expecting record {$row} {$field} in table {$ds->fields[0]->table} to be {$value}");
		}


		/**
		 * assert DataSet count
		 *
		 * @param <type> $ds
		 * @param <type> $count
		 */
		protected function assertDataSetCount(\System\Data\DataSet $ds, $count)
		{
			$this->assertEqual($ds->count, $count, "Expecting {$count} records in table {$ds->fields[0]->table}");
		}
	}
?>