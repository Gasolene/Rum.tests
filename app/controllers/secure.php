<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;
    use System\Data\DataAdapter;
    use MyApp\App;

	class Secure extends \MyApp\ApplicationController
	{
		protected $ssl = true;

		function onPageInit( &$page, $args ) {
			
		}
	}
?>