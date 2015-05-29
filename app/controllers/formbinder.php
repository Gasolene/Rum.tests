<?php
    namespace MyApp\Controllers;

	class FormBinder extends \MyApp\ApplicationController
    {
		function onPageInit( &$sender, $args )
		{
			$this->page->add(\MyApp\Models\Customer::form('form'));
			$this->page->form->ajaxPostBack = true;
		}

		function onPageLoad( &$sender, $args )
		{
			$this->page->form->dataSource = \MyApp\Models\Customer::create();
		}

		function onSaveAjaxPost( &$sender, $args )
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