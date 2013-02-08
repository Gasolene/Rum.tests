<?php

	namespace TestDocumentor;

	class TestDocumentor
	{
		/**
		 * preconditions rendered?
		 * @var bool
		 */
		static private $preconditions = false;


		/**
		 * Constructor
		 * @return  void
		 */
		public function __construct() {
			if(!defined('__TEST_DOCUMENTOR__')) {
				// create new output files
				define('__TEST_DOCUMENTOR__', true);

				$output = "<!DOCTYPE html>\n";
				$output .= "<html lang=\"en\">\n";
				$output .= "<head>\n";
				$output .= "<style type=\"text/css\">\n";
				//$output .= "table {width:100%;}\n";
				$output .= "</style>\n";
				$output .= "</head>\n";
				$output .= "<body>\n";
				\file_put_contents(\System\Web\WebApplicationBase::getInstance()->config->logs.'/test_results.html', $output);
				\file_put_contents(\System\Web\WebApplicationBase::getInstance()->config->logs.'/test_results.csv', "Process/Component,Test Name,Description,Entrance Conditions,Basic Flow,Exit Conditions,Results\n");
			}
		}


		public function __destruct()
		{
			$fp = \fopen(\System\Web\WebApplicationBase::getInstance()->config->logs.'/test_results.html', 'a+');

			$output = "</body>\n";
			$output .= "</html>\n";

			\fwrite($fp, $output);
			\fclose($fp);
		}

		/**
		 * Document test case
		 *
		 * @param TestCase $testCase TestCase to add
		 * @return void
		 */
		public function documentTestCase(TestCase $testCase) {
			$this->documentCSVTestCase($testCase);
			$this->documentHTMLTestCase($testCase);
		}


		/**
		 * Document CSV test case
		 *
		 * @param TestCase $testCase TestCase to add
		 * @return string
		 */
		private function documentCSVTestCase(TestCase $testCase) {
			$fp = \fopen(\System\Web\WebApplicationBase::getInstance()->config->logs.'/test_results.csv', 'a+');

			$fixtures = $testCase->getFixtures();

			foreach($testCase->getTests() as $test)
			{
				if($test->getAuthenticatedUser())
				{
					$entrance_conditions = "Authenticate user: '{$test->getAuthenticatedUser()}'\n" . $fixtures;
				}
				else
				{
					$entrance_conditions = $fixtures;
				}

				$flows = $test->getTestFlows();
				$results = $test->getTestResults();

				if($flows && $results)
				{
					foreach($results as $result)
					{
						$exit_condition = $result[0];
						$pass = $result[1];

						$flow = '';
						foreach($flows as $flow=>$values)
						{
							if($values)
							{
								$flow .= "\n";
								foreach($values as $key=>$val)
								{
									$flow .= "{$key} = {$val}\n";
								}
							}

							\fputcsv($fp, array($testCase->getName(), $test->getDescription(), $testCase->getDescription(), $entrance_conditions, $flow, $exit_condition, $pass?'Pass':'Fail'), ',', '"');
						}
					}
				}
			}

			\fclose($fp);
		}


		/**
		 * Document HTML test case
		 *
		 * @param TestCase $testCase TestCase to add
		 * @return string
		 */
		private function documentHTMLTestCase(TestCase $testCase) {
			$fp = \fopen(\System\Web\WebApplicationBase::getInstance()->config->logs.'/test_results.html', 'a+');

			$output = '';

			if(!self::$preconditions)
			{
				self::$preconditions = true;
				$output .= "<h1>Entrance Conditions</h1>\n";
				$output .= "<p>This is a runtime snapshot of the database at the time of testing. The database state is reset for every test.</p>";

				foreach($testCase->getPreConditions() as $table=>$rows)
				{
					if($rows)
					{
						$fields = array_keys($rows[0]);
						$output .= "<table border=1>\n";
						$output .= "  <caption>{$table}</caption>\n";
						$output .= "  <tr>\n";

						foreach($fields as $field)
						{
							$output .= "    <th>{$field}</th>\n";
						}

						$output .= "  </tr>\n";

						foreach($rows as $row)
						{
							$output .= "  <tr>\n";

							foreach($row as $col)
							{
								$output .= "    <td>".\htmlentities($col)."</td>\n";
							}

							$output .= "  </tr>\n";
						}

						$output .= "</table>\n";
					}
				}
			}

			$output .= "<h1>Test Case: {$testCase->getName()}</h1>\n";
			$output .= "<p>Description: {$testCase->getDescription()}</p>\n";

			foreach($testCase->getTests() as $test)
			{
				$flows = $test->getTestFlows();
				$results = $test->getTestResults();

				if($flows && $results)
				{
					$output .= "<h2>Test Name: {$test->getDescription()}</h2>\n";

					foreach($flows as $description=>$values)
					{
						if($test->getAuthenticatedUser())
						{
							$output .= "<h3>Authenticated User: {$test->getAuthenticatedUser()}</h3>\n";
						}

						$output .= "<h3>Basic Flow: {$description}</h3>\n";

						if($values)
						{
							$output .= "<table border=1>\n";
							$output .= "  <caption>Test Values</caption>\n";
							$output .= "  <tr>\n";
							$output .= "    <th>Field</th>\n";
							$output .= "    <th>Value</th>\n";
							$output .= "  </tr>\n";

							foreach($values as $key=>$val)
							{
								$output .= "  <tr>\n";
								$output .= "    <td>{$key}</td>\n";
								$output .= "    <td>{$val}</td>\n";
								$output .= "  </tr>\n";
							}

							$output .= "</table>\n";
						}
					}

					$output .= "<h3>Results</h3>\n";

					$output .= "<table border=1>\n";
					$output .= "  <caption>Results</caption>\n";
					$output .= "  <tr>\n";
					$output .= "    <th>Description</th>\n";
					$output .= "    <th>Pass/Fail</th>\n";
					$output .= "  </tr>\n";

					foreach($results as $result)
					{
						$description = $result[0];
						$pass = $result[1];

						$output .= "  <tr>\n";
						$output .= "    <td>{$description}</td>\n";
						$output .= "    <td ".($pass?'class=\"pass\">Pass':'class=\"fail">Fail')."</td>\n";
						$output .= "  </tr>\n";
					}

					$output .= "</table>\n";
				}
			}

			\fwrite($fp, $output);
			\fclose($fp);
		}
	}
?>