<?php
    namespace MyApp\Controllers;
    use \System\UI\WebControls;

	class CKEditor extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args )
		{
			$this->page->add( new WebControls\Form( 'form' ));
			$this->page->form->add( new \CKEditor\CKEditor( 'ckeditor1' ));
			$this->page->form->add( new \CKEditor\CKEditor( 'ckeditor2' ));

			$this->page->form->add( new WebControls\Button( 'submit' ));
		}
	}
?>