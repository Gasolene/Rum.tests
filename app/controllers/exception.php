<?php
    namespace MyApp\Controllers;
    use \System\UI\WebControls;

	class Exception extends \MyApp\ApplicationController {

		function onPageInit( &$page, $args ) {
			$a = $b;

			$db = $this->dataAdapter;
			$db->invalidProperty;
		}

		function throwException($string) {
			throw new \Exception($string);
		}
	}
?>