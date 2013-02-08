<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;
    use System\UI\WebControls\Page;

	class Login extends \MyApp\ApplicationController
	{
		public function onPageInit( &$page, $args )
        {
			$this->page->add( new WebControls\LoginForm( 'login_form' ));
		}
	}
?>