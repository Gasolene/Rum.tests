<?php
    namespace MyApp\Controllers;

    use \System\Db\DataAdapter;
	use \System\Web\WebControls;
	use \System\Validators;

	class Ajax extends \MyApp\ApplicationController
	{
		protected $company;

		function onPageInit($sender, $args)
		{
			\Rum::trace("Postback trace call");

			// create Form
			$this->page->enableViewState = false;
			$this->page->add(new WebControls\Form('form'));
			$this->page->form->legend = 'Ajax Form';
			$this->page->form->add(new WebControls\Text('name'));
			$this->page->form->add(new WebControls\Email('email'));
			$this->page->form->add(new \CommonControls\CountrySelector('country'));
			$this->page->form->add(new WebControls\DropDownList('city'));
			$this->page->form->add(new WebControls\Button('submit'));
			$this->page->form->add(new WebControls\Button('cancel'));
			$this->page->form->add(new WebControls\Button('show_only_active'));
			$this->page->form->add(new WebControls\Button('postback'));
			$this->page->form->name->addValidator(new Validators\RequiredValidator('You must enter" a \'name!'));
			$this->page->form->email->addValidator(new Validators\RequiredValidator('You must enter an email address!'));
			$this->page->form->ajaxValidation = true;
			$this->page->form->name->placeholder = 'Enter your username here...';
			$this->page->form->name->tooltip = 'Enter your username here...';
			$this->page->form->email->tooltip = 'Enter your email address here...';
			$this->page->form->email->placeholder = 'Enter your email address here...';
			$this->page->form->country->tooltip = 'Select your country here...';
			$this->page->form->city->tooltip = 'Select your city here...';
			$this->page->form->email->addValidator(new \System\Validators\EmailValidator());

			// create GridView
			$this->page->add( new WebControls\GridView( 'table' ));
			$this->page->table->caption = 'Ajax GridView';
			$this->table->columns->add(new WebControls\GridViewText('Company', 'Company', 'Company', 'Company'));
			$this->table->columns->add(new WebControls\GridViewCheckBox('Active', 'Company', 'Active', 'Active'));
			$this->table->columns->add(new WebControls\GridViewDropDownList('Objective', 'Company', array('Marketing relationship'=>'Marketing relationship'
				, 'Distribution agreement'=>'Distribution agreement'
				, 'Product integration'=>'Product integration'
				, 'Co-sell situation'=>'Co-sell situation'
				), '', 'Objective'));
			$this->table->columns->add(new WebControls\GridViewButton('Company', 'Edit', 'ActionButton', '', '', '', '', 'Add'));
			$this->table->columns[0]->setFilter(new WebControls\GridViewStringFilter());
			$this->table->columns[1]->setFilter(new WebControls\GridViewBooleanFilter());
			$this->table->columns[2]->setFilter(new WebControls\GridViewListFilter(DataAdapter::create( 'driver=text;format=CommaDelimited;source=' . \Rum::config()->root . '/app/data/Partner Tracking List.csv' )->openDataSet()->rows));
			$this->table->columns[2]->filter->textField = 'Objective';
			$this->table->columns[2]->filter->valueField = 'Objective';
			$this->table->showFilters = true;
			$this->table->showFooter = false;
			$this->table->showInsertRow = true;
			$this->table->ajaxPostBack = true;
			$this->table->columns->ajaxPostBack = true;
			$this->table->pageSize = 5;
		}

		function onPageLoad($sender, $args)
		{
			$ds = DataAdapter::create( 'driver=text;format=CommaDelimited;source=' . \Rum::config()->root . '/app/data/Partner Tracking List.csv' )->openDataSet();
			$this->table->bind($ds);
		}

		function onPageRequest($sender, $args)
		{
			$ds = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Cities.csv' )->openDataSet();
			$ds->filter('Country', '=', $this->country->value);

			$this->city->textField = 'Name of city';
			$this->city->valueField = 'code';
			$this->city->dataSource = $ds;

			if($this->isAjaxPostBack) {
//				\Rum::flash("AJAX post back detected");
			}
		}

		function onCityChange($sender, $args)
		{
			$this->name->value = $this->city->value . ', ' . $this->country->value;
		}

		// Form Ajax
		function onFormAjaxPost($sender, $args)
		{
		}

		// show_only_active Ajax
		function onShow_only_activeAjaxClick($sender, $args)
		{
			$ds = DataAdapter::create( 'driver=text;format=CommaDelimited;source=' . \Rum::config()->root . '/app/data/Partner Tracking List.csv' )->openDataSet('', \System\DB\DataSetType::openStatic());
			$ds->filter('Active', '=', true);
			$this->table->dataSource = $ds;

			$this->table->updateAjax();
		}

		// Submit Ajax
		function onSubmitAjaxClick($sender, $args)
		{
			\Rum::trace("Async trace call");

			if($this->form->validate($err))
			{
				$this->page->loadAjaxJScriptBuffer("alert('form submitted');");
			}
			else
			{
				\Rum::flash('f:'.trim($err));
			}
		}

		// Cancel Ajax
		function onCancelAjaxClick($sender, $args)
		{
			\Rum::trace("Async trace call");
			\Rum::forward();
		}

		// ListBox Ajax
		function onCountryAjaxChange($sender, $args)
		{
			$this->name->updateAjax();
			$this->city->updateAjax();
		}

		// City Ajax
		function onCityAjaxChange($sender, $args)
		{
			$this->name->updateAjax();
		}

		// GridView Ajax
		function onCompanyAjaxPost($sender, $args)
		{
			\Rum::flash("{$args["Company"]} posted");
			$this->company = $args["Company"];
		}

		// GridView Ajax
		function onActionButtonAjaxPost($sender, $args)
		{
			\Rum::flash("{$this->company} @set");
			\Rum::flash("{$args["Company"]} @clicked");
		}
	}
?>