<?php
    namespace MyApp\Controllers;
    use \System\UI\WebControls;

	class TinyMCE extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args )
		{
			$this->page->add( new WebControls\Form( 'form' ));
			$this->page->form->add( new \TinyMCE\TinyMCE( 'tinymce1' ));

			$this->page->form->add( new WebControls\Button( 'submit' ));
		}
	}
?>