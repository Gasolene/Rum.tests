<?php
    namespace MyApp\Controllers;

	class Confirm extends \MyApp\ApplicationController {
		function onPageInit(&$sender, $args) {
			$this->page->add(new \System\Web\WebControls\Form('form'));
			$this->page->form->legend = 'Password Confirmation Test';
			$this->page->form->add(new \System\Web\WebControls\Password('password1'));
			$this->page->form->add(new \System\Web\WebControls\Password('password2'));
			$this->page->form->add(new \System\Web\WebControls\Button('submit'));

			$this->page->form->password2->addValidator(new \System\Validators\CompareValidator('password1'));
			$this->page->form->ajaxValidation = true;
		}

		function onSubmitClick(&$sender, $args) {
			
		}
	}
?>