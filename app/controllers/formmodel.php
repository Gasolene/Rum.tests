<?php
    namespace MyApp\Controllers;

	class FormModel extends \MyApp\ApplicationController
    {
		function onPageInit( &$sender, $args )
		{
			$this->page->add(\MyApp\MyForm::form('form'));
		}

		function onPageLoad( &$sender, $args )
		{
			$ds = \MyApp\MyForm::create();
			$this->page->form->attachDataSource($ds);
		}

		function onSaveClick( &$sender, $args )
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();
			}
			else
			{
				\Rum::flash("f:{$err}");
			}
		}
    }
?>