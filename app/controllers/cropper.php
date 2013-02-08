<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;

	class Cropper extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {

			// create form
			$this->page->add( new WebControls\Form( 'form' ));

			$this->form->add(\MyApp\UI::TextBox('title'));
			$this->form->add(new \Mootools\FacebookCropper('image'));
			$this->form->add(\MyApp\UI::TextBox('caption'));
			$this->form->add(new WebControls\Button('save'));

			$this->title->addValidator(new \System\UI\Validators\RequiredValidator("You must enter a title!"));
			$this->caption->addValidator(new \System\UI\Validators\RequiredValidator());
			$this->caption->multiline = true;

			//$this->title->ajaxPostBack = true;
			//$this->image->ajaxPostBack = true;
			//$this->form->ajaxPostBack = true;
		}

		function onFormPost( \System\UI\WebControls\Form &$form, $args ) {
			if($this->image->value)
			{
				$img_orig = new \GD\GDImage();
				$img_orig->loadStream($this->image->getFileRawData());

				$img_resized = new \GD\GDImage();
				$img_resized->loadStream(\file_get_contents($this->image->value));

				$img_resized->render();
			}
		}
	}
?>