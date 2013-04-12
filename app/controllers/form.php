<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;

	class Form extends \MyApp\ApplicationController
	{
		//protected $allowRoles = array('Custom', 's');

		function onPageInit( &$page, $args ) {

//strt();
			// create datagrid
			$this->page->add( new WebControls\GridView( 'table' ));
			$this->table->autoGenerateColumns = true;
			$this->table->caption = 'Sample Table';
			$this->table->showFilters = true;

			// create form
			$this->page->enableViewState = true;
			$this->page->add( new WebControls\Form( 'form' ));
			//$this->page->form->legend = 'Sample Form';
			$this->page->form->enableViewState = true;
			$this->page->form->autoFocus = true;

			$this->page->form->add( new WebControls\Fieldset( 'fieldset1' ));
			$this->page->form->add( new WebControls\Fieldset( 'fieldset2' ));
			$this->page->form->fieldset1->legend = 'Sample Fieldset';

			$this->form->add( \MyApp\UI::TextBox( 'Name', '' ));
			$this->form->autoFocus = true;
			$this->fieldset1->add( new \CommonControls\AddressInput( 'Address' ));
			$this->fieldset1->add( new \SuggestBox\SuggestBox( 'City', 'Calgary' ));
			$this->fieldset1->add( new \CommonControls\ProvinceStateSelector( 'Province', 'AB' ));
			$this->fieldset1->add( new \CommonControls\CountrySelector( 'Country', 'CA' ));
			$this->fieldset1->add( new \CommonControls\PostalZipCodeInput( 'Postal/Zip Code' ));
			$this->fieldset1->add( new WebControls\RadioGroup( 'Sex', 'f' ));
			$this->Sex->add( new WebControls\RadioButton( 'f', true ));
			$this->Sex->add( new WebControls\RadioButton( 'm' ));
			$this->fieldset1->add( new \CommonControls\PhoneNumberInput( 'Phone No' ));
			$this->fieldset1->add( new \CommonControls\EmailAddressInput( 'E-Mail Address' ));
			$this->fieldset2->add( new \DatePicker\DatePicker( 'Birthday' ));
			$this->fieldset2->add( new WebControls\CheckBoxList( 'favoritecolors' ));
			$this->fieldset2->add( new \ColorPicker\ColorPicker( 'Favorite Color' ));
			$this->fieldset2->add( new WebControls\CheckBox( 'Active', true ));
			$this->fieldset2->add( \MyApp\UI::DateTimeSelector( 'DateTime' ));
			$this->fieldset2->add( \MyApp\UI::DateSelector( 'Date' ));
			$this->fieldset2->add( \MyApp\UI::TimeSelector( 'Time' ));
			$this->fieldset2->add( new \System\Web\WebControls\TextArea( 'Comment' ));
			$this->fieldset2->add( new \CommonControls\PercentInput( 'percent' ));
			$this->fieldset2->add( new \CommonControls\MoneyInput( 'price' ));
			$this->fieldset2->add( new \CommonControls\URLInput( 'URL' ));
			$this->fieldset2->add( new \CommonControls\TitleSelector( 'title' ));
			$this->fieldset2->add( new \CommonControls\YearInput( 'year' ));
			$this->fieldset2->add( new \CommonControls\ZipCodeInput( 'zip' ));
			$this->fieldset2->add( new \CommonControls\PostalCodeInput( 'postal' ));
			$this->fieldset2->add( new \CommonControls\ProvinceSelector( 'prov' ));
			$this->fieldset2->add( new \CommonControls\StateSelector( 'state' ));
			$this->fieldset2->add( new \CommonControls\ImageUpload( 'image' ));

			$this->Name->addValidator(new \System\UI\Validators\RequiredValidator("You must enter a name!"));
			$this->Name->addValidator(new \System\UI\Validators\RequiredValidator());
			$this->form->Province->attachCountrySelector($this->form->Country);
			$this->form->prov->attachCountrySelector($this->form->Country);
			$this->form->state->attachCountrySelector($this->form->Country);

			$this->City->addValidator(new \System\UI\Validators\RequiredValidator());
			$this->Province->addValidator(new \System\UI\Validators\RequiredValidator());

			$this->title->items->add( '', '' );
			$this->Province->validators[0]->errorMessage = 'blast off';

			$this->favoritecolors->items->add( 'Red', '#FF0000' );
			$this->favoritecolors->items->add( 'Green', '#00FF00' );
			$this->favoritecolors->items->add( 'Blue', '#0000FF' );

			$this->Sex->m->label = 'Male';
			$this->Name->tooltip = 'Enter your name here tooltip';

			$this->Sex->f->label = 'Female';

			$this->form->add( new WebControls\Button( 'save', 'Save' ));

			//$this->Name->ajaxPostBack = true;
			//$this->form->ajaxPostBack = true;
			$this->Date->ajaxValidation = true;
			$this->Time->ajaxValidation = true;
			$this->DateTime->ajaxValidation = true;
		}

		function onPageLoad( &$page, $args ) {


			$db = \System\Data\DataAdapter::create( 'driver=text;format=TabDelimited;source=' . __ROOT__ . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet();

			if( isset( $_REQUEST["id"] )) {
				if( !$rs->seek( 'Name', $_REQUEST["id"] )) {
					$rs->first();
				}
			}

			// data binding
			$this->City->textField  = 'City';
			$this->City->dataSource = clone $rs;

			$this->table->dataSource = $rs;

			$this->form->dataSource = $rs;
		}

		function onNameChange( $sender, $args ) {
			\Rum::flash("Name was changed");
		}

		function onFormPost( &$form, $args ) {

			$err = '';
			if( $this->form->validate($err) ) {
				$this->form->save();

				\Rum::flash("s:record saved");
				\Rum::forward();
			}
			else {
				\Rum::flash("f:{$err}");
			}
		}
/**
		protected function onPreRender( &$page, $args )
		{
			//stp();
		}
 */
		public function onBirthdayAjaxPost()
		{
			\Rum::flash($this->Birthday->value);
		}
	}
?>