<?php
    namespace MyApp\Controllers;
    use System\UI\WebControls;
    use System\Data\DataAdapter;
    use MyApp\App;

	class GridView extends \MyApp\ApplicationController
	{
		//protected $outputCache = 10;

		function onPageInit(&$page, $args ) {

			// create datagrid
			$this->page->add( new WebControls\GridView( 'table1' ));
			$this->table1->pageSize = 10;
			$this->table1->showList = true;
			$this->table1->multiple = true;
			$this->table1->valueField = 'Company';
			$this->table1->canChangeOrder = true;
			$this->table1->orderByField = 'Sort Order';
			$this->table1->ajaxPostBack = true;

			$this->page->add( new WebControls\GridView( 'table2' ));
			$this->table2->addColumn( new WebControls\GridViewColumn( 'Company', 'Company', '"<a href=\"mailto:".%ContactEmail%."\">".%Company%."</a>"', '\'CompanyFooter\'', 'company_class' ));
			$this->table2->addColumn( new WebControls\GridViewColumn( 'ContactPhone', 'Contact Phone' ));
			$col = new WebControls\GridViewColumn( 'Last Activity' );
			$col->ondblclick = 'alert(\'Column click event triggered: %Last Activity%\');';
			$this->table2->addColumn( $col );
			// $this->table2->addActionColumn( \Rum::uri( 'form' ), 'Contact', 'contact' );
			$this->table2->addColumn( new WebControls\GridViewButton( 'Company', 'Edit' ));
			$this->table2->showFooter = true;

			$this->table1->selected = array('Amazon.com');
			$this->table1->showFilters = true;

			$this->table1->setFilterValues( 'Last Activity', array('Phone call', 'Webinar', 'Meeting' ));
		}

		function onPageLoad( &$page, $args ) {
			
			$db = DataAdapter::create( 'driver=text;format=CommaDelimited;source=' . \Rum::config()->root . '/app/data/Partner Tracking List.csv' );
			$ds = $db->openDataSet();

			$this->table1->dataSource = $ds;
			$this->table2->dataSource = $db->openDataSet();
		}

		function onCompanyAjaxPost( &$col, $args ) {
//			dmp('ww');
			\Rum::flash($args["Company"] . " Clicked");
//			\Rum::forward();
		}
	}
?>