<?php // all controller tests should inherit this class

	namespace TestDocumentor;

	abstract class ControllerTestCaseWriter extends \System\Testcase\ControllerTestCaseBase
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
		 * form test?
		 * @var bool
		 */
		static private $formTest = false;


		/**
		 * Constructor
		 *
		 * @param  string $testCase name of test case
		 * @return void
		 */
		public function __construct($testCase = '') {
			if(!self::$testDocumentor) self::$testDocumentor = new TestDocumentor ();
			parent::__construct($testCase);

			$this->testCase = new TestCase($this->_label, "This test case will test the request/response cycle for the /{$testCase}/ page");
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
		 * simulate a get request
		 *
		 * @param   array		$params		data to send
		 * @return  void
		 */
		protected function get( array $params = array() ) {
			parent::get($params);

			// add test flow
			self::$test->addTestFlow("GET Request /".$this->controller->controllerId . $this->paramsToString($params));
		}


		/**
		 * simulate a post request
		 *
		 * @param   array		$params		data to send
		 * @return  void
		 */
		protected function post( array $params = array() ) {
			parent::post($params);

			// add test flow
			if(!self::$formTest)
			{
				self::$test->addTestFlow("POST Request /".$this->controller->controllerId, $params);
			}
			else
			{
				self::$formTest = false;
			}
		}


		/**
		 * simulate a form submition
		 *
		 * @param string $formId id of the form
		 * @param array $data
		 */
		protected function submit( $formId, array $data = array() )
		{
			self::$formTest = true;
			parent::submit($formId, $data);

			// add test flow
			self::$test->addTestFlow("Submit {$formId} on /".$this->controller->controllerId . " with values below", $data);
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
		 *
		 * @param <type> $string
		 */
		final public function assertResponse($string)
		{
			$message = "Expecting the following string in the response `{$string}`";
			$retval = parent::assertResponse($string, $message);
			return $retval;
		}

		/**
		 *
		 * @param <type> $forward
		 * @param array $forwardParams
		 */
		final public function assertRedirectedTo($forward, array $forwardParams = array())
		{
			$message = "Expecting to be redirected to {$forward}" . $this->paramsToString($forwardParams);
			$retval = parent::assertRedirectedTo($forward, $forwardParams, $message);
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

		/**
		 * assert validation passes
		 * @param <type> $control
		 */
		final public function assertValidationPasses(\System\Web\WebControls\WebControlBase $control)
		{
			$message = "Expecting validation to pass for {$control->controlId}";
			$retval = parent::assertTrue($control->validate($err));
			self::$test->addTestResult($message, $retval);
			return $retval;
		}

		/**
		 * assert validation fails
		 * @param <type> $control
		 */
		final public function assertValidationFails(\System\Web\WebControls\WebControlBase $control)
		{
			$message = "Expecting validation to fail for {$control->controlId}";
			$retval = parent::assertFalse($control->validate($err));
			self::$test->addTestResult($message, $retval);
			return $retval;
		}

		/**
		 *
		 * @param <type> $params
		 * @return <type>
		 */
		private function paramsToString($params)
		{
			$str = "";
			foreach($params as $key=>$val) {
				if($str) {
					$str .= "&{$key}={$val}";
				}
				else {
					$str .= "?{$key}={$val}";
				}
			}
			return $str;
		}
	}
?>