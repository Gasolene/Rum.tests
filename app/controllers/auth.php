<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;
    use System\Data\DataAdapter;
	use System\UI\Ajax\JScriptBuffer;
    use MyApp\App;
	use MyApp\UI;

	class Auth extends \MyApp\ApplicationController
	{
		function onPageInit(&$page, $args )
		{
			$this->page->assign('user', \System\Security\Authentication::$identity);
		}
	}
?>