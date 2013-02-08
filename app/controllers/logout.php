<?php
    namespace MyApp\Controllers;

	class Logout extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {

			\System\Security\Authentication::signout();
			\Rum::forward('login');
		}
	}
?>