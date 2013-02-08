<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;

	class Match extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {
			

			// create form
			$this->page->add( new WebControls\Form( 'form' ));
			$this->form->add( new \CommonControls\PasswordInput( 'field1' ));
			$this->form->add( new \CommonControls\PasswordInput( 'field2' ));
			$this->form->add( new \CommonControls\PasswordInput( 'field3' ));

			$this->form->add( new WebControls\Button( 'Submit' ));
			$this->page->form->ajaxValidation = true;

			$this->form->field2->addValidator(new \System\UI\Validators\MatchValidator($this->field1));
			$this->form->field2->addValidator(new \System\UI\Validators\MatchValidator($this->field3));
		}

		public function onPageLoad($sender, $args)
		{
			
		}

		function onFormPost( \System\UI\WebControls\Form &$form, $args )
		{
			$err = "";
			if( $form->validate($err) )
			{
				\Rum::flash("s:Processing");
			}
			else
			{
				\Rum::flash("f:".$err);
			}
		}
	}
?>