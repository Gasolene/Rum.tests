<?php
    namespace MyApp\Controllers;
    use \System\UI\WebControls;

	class FCKEditor extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args )
		{
			$this->page->add( new WebControls\Form( 'form' ));
			$this->page->form->add( new \FCKEditor\FCKEditor('fckeditor1'));
			$this->page->form->add( new \FCKEditor\FCKEditor('fckeditor2'));

			$this->page->form->add( new WebControls\Button( 'submit' ));
		}
	}
?>