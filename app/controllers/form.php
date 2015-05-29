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
			$this->page->form->enableViewState = true;

			$this->form->add( new \System\Web\WebControls\Text( 'Name', '' ));
			$this->form->add( new \CommonControls\AddressInput( 'Address' ));
			$this->form->add( new \System\Web\WebControls\Search( 'City', 'Calgary' ));
			$this->form->add( new \CommonControls\ProvinceStateSelector( 'Province', 'AB' ));
			$this->form->add( new \CommonControls\CountrySelector( 'Country', 'CA' ));
			$this->form->add( new \CommonControls\PostalZipCodeInput( 'Postal/Zip Code' ));
			$this->form->add( new WebControls\RadioButton( 'f', 'Sex', true ));
			$this->form->add( new WebControls\RadioButton( 'm', 'Sex' ));
			$this->form->add( new \System\Web\WebControls\Tel( 'Phone No' ));
			$this->form->add( new \System\Web\WebControls\Email( 'E-Mail Address' ));
			$this->form->add( new \System\Web\WebControls\Date( 'Birthday' ));
			$this->form->add( new WebControls\CheckBoxList( 'favoritecolors' ));
			$this->form->add( new \System\Web\WebControls\Color( 'Favorite Color' ));
			$this->form->add( new WebControls\CheckBox( 'Active', true ));
			$this->form->add( new \System\Web\WebControls\Date( 'Date' ));
			$this->form->add( new \System\Web\WebControls\Time( 'Time' ));
			$this->form->add( new \System\Web\WebControls\TextArea( 'Comment' ));
			$this->form->add( new \CommonControls\PercentInput( 'percent' ));
			$this->form->add( new \CommonControls\MoneyInput( 'price' ));
			$this->form->add( new \System\Web\WebControls\URL( 'URL' ));
			$this->form->add( new \CommonControls\TitleSelector( 'title' ));
			$this->form->add( new \CommonControls\YearInput( 'year' ));
			$this->form->add( new \CommonControls\ZipCodeInput( 'zip' ));
			$this->form->add( new \CommonControls\PostalCodeInput( 'postal' ));
			$this->form->add( new \CommonControls\ProvinceSelector( 'prov' ));
			$this->form->add( new \CommonControls\StateSelector( 'state' ));
			$this->form->add( new \CommonControls\ImageUpload( 'image' ));

			$this->Name->addValidator(new \System\UI\Validators\RequiredValidator("You must enter a name!"));
			$this->Name->addValidator(new \System\UI\Validators\RequiredValidator());
			$this->form->Province->attachCountrySelector($this->form->Country);
			$this->form->prov->attachCountrySelector($this->form->Country);
			$this->form->state->attachCountrySelector($this->form->Country);

			$this->City->addValidator(new \System\UI\Validators\RequiredValidator());
			$this->Province->addValidator(new \System\UI\Validators\RequiredValidator());
			$this->form->getControl( 'E-Mail_Address' )->addValidator(new \System\Validators\EmailValidator());
			$this->form->Birthday->addValidator(new \System\Validators\DateTimeValidator());
			$this->form->Date->addValidator(new \System\Validators\DateTimeValidator());
			$this->form->Time->addValidator(new \System\Validators\DateTimeValidator());

			$this->title->items->add( '', '' );
//			$this->Province->validators[0]->errorMessage = 'blast off';

			$this->favoritecolors->items->add( 'Red', '#FF0000' );
			$this->favoritecolors->items->add( 'Green', '#00FF00' );
			$this->favoritecolors->items->add( 'Blue', '#0000FF' );

			$this->form->add( new WebControls\Button( 'save', 'Save' ));

			$this->Name->ajaxPostBack = true;
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
//			$this->City->textField  = 'City';
//			$this->City->dataSource = clone $rs;

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