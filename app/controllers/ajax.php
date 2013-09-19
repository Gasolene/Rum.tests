<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;
    use System\Data\DataAdapter;
	use System\UI\Ajax\JScriptBuffer;
    use MyApp\App;
	use MyApp\UI;

	class Ajax extends \MyApp\ApplicationController
	{
		function onPageInit(&$page, $args )
		{
			\Rum::db()->openDataSet("select * from test");
\Rum::trace("Postback trace call");
			// create Form
			$this->page->add(\MyApp\UI::Form('form'));
			$this->page->form->add(\MyApp\UI::TextBox('name'));
			$this->page->form->add(new \CommonControls\EmailAddressInput('email'));
			$this->page->form->add(new \CommonControls\CountrySelector('country'));
			$this->page->form->add(\MyApp\UI::DropDownList('city'));
			$this->page->form->add(\MyApp\UI::Button('submit'));
			$this->page->form->add(\MyApp\UI::Button('cancel'));
			$this->page->form->add(\MyApp\UI::Button('show_only_active'));
			$this->page->form->add(\MyApp\UI::Button('postback'));
			$this->page->form->name->addValidator(\MyApp\UI::RequiredValidator('You must enter" a \'name!'));
			$this->page->form->email->addValidator(\MyApp\UI::RequiredValidator('You must enter an email address!'));
			$this->page->form->ajaxValidation = true;
			$this->page->form->name->watermark = 'Enter your username here...';
			$this->page->form->email->watermark = 'Enter your email address here...';
			$this->page->form->name->tooltip = 'Enter your username here...';
			$this->page->form->email->tooltip = 'Enter your email address here...';

			// create GridView
			$this->page->add( new WebControls\GridView( 'table' ));
			$this->table->columns->add(new \System\UI\WebControls\GridViewTextBox('Company', 'Company', 'NewCompany', 'Company'));
			$this->table->columns->add(new \System\UI\WebControls\GridViewCheckBox('Active', 'Company', '', 'Active'));
			$this->table->columns->add(new \System\UI\WebControls\GridViewDropDownMenu('Objective', 'Company', array('Marketing relationship'=>'Marketing relationship'
				, 'Distribution agreement'=>'Distribution agreement'
				, 'Product integration'=>'Product integration'
				, 'Co-sell situation'=>'Co-sell situation'
				), '', 'Objective'));
			$this->table->columns->add(new \System\UI\WebControls\GridViewButton('Company', 'Edit', 'CompanyName'));
		}

		function onPageLoad( &$page, $args )
		{
			$this->table->dataSource = DataAdapter::create( 'driver=text;format=CommaDelimited;source=' . \Rum::config()->root . '/app/data/Partner Tracking List.csv' )->openDataSet();
		}

		function onPageRequest( &$page, $args )
		{
			$ds = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Cities.csv' )->openDataSet();
			$ds->filter('Country', '=', $this->country->value);

			$this->city->textField = 'Name of city';
			$this->city->valueField = 'code';
			$this->city->dataSource = $ds;
		}

		function onCityChange($sender, $args)
		{
			$this->name->value = $this->city->value . ', ' . $this->country->value;
		}

		// Form Ajax
		function xonFormAjaxPost($sender, $args)
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

		// TextBox Ajax
		function onNameAjaxChange($sender, $args)
		{
			//$this->page->loadAjaxJScriptBuffer("alert('name changed to {$this->name->value}');");
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
		function onNewCompanyAjaxPost($sender, $args)
		{
			$this->page->loadAjaxJScriptBuffer("alert('Company: {$args["Company"]} name changed to: {$args["NewCompany"]}');");
		}

		function onActiveAjaxPost($sender, $args)
		{
			$this->page->loadAjaxJScriptBuffer("alert('Company: {$args["Company"]} active changed to: ".($args["Active"]=='true'?'true':'false')."');");
		}

		function onObjectiveAjaxPost($sender, $args)
		{
			$this->page->loadAjaxJScriptBuffer("alert('Company: {$args["Company"]} objective changed to: {$args["Objective"]}');");
		}

		function onCompanyNameAjaxPost($sender, $args)
		{
			$this->page->loadAjaxJScriptBuffer("alert('Company: {$args["CompanyName"]} clicked');");
		}

		function onCompanyNamePost($sender, $args)
		{
			\Rum::flash("{$args["CompanyName"]} clicked");
		}

		function onEditClick($sender, $args)
		{
			\Rum::flash("deprecated:{$args["Company"]} clicked");
		}
	}
?>