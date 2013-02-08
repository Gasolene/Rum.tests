<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;

	class LoadTest extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {

			// create datagrid
			$this->page->add( new WebControls\GridView( 'table' ));
			$this->table->autoGenerateColumns = true;
			$this->table->caption = 'Table';
			$this->table->showFilters = true;

			$this->page->add( new WebControls\Form( 'form' ));
			$this->page->form->add( new WebControls\DropDownList( 'dropdownlist' ));
		}

		function onPageLoad( &$page, $args ) {
//strt();
			$db = \System\Data\DataAdapter::create( 'driver=mysqli;uid=darnell;pwd=R146D;server=samwise;database=darnell_pwimporter;' );
			$rs = $db->openDataSet("select * from invoices");
//stp();
//strt();
			$this->table->dataSource = $rs;
//stp();
//strt();
			$this->dropdownlist->textField = 'invoice_number';
			$this->dropdownlist->valueField = 'invoice_id';
			$this->dropdownlist->dataSource = $rs;
//stp();
		}
	}

	// 3.835s data retrieval before
	// 0.085s data retrieval after

	// 4.2s load
	// 11MB
?>