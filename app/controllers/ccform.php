<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;

	class CCForm extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {
			

			// create form
			$this->page->add( new WebControls\Form( 'form' ));
			$this->page->form->legend = 'Credit Card Information';

			$this->form->add( new WebControls\TextBox( 'name_on_card' ));
			$this->form->add( new \CommonControls\CardTypeSelector( 'card_type' ));
			$this->form->add( new \CommonControls\CardNumberInput( 'card_number' ));
			$this->form->add( new \CommonControls\CardExpirationDateSelector( 'card_expiration_date' ));
			$this->form->add( new \CommonControls\CardSecurityCodeInput( 'card_CVV2' ));

			$this->form->add( new WebControls\Button( 'Submit' ));
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