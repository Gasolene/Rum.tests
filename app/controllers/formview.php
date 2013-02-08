<?php
    namespace MyApp\Controllers;

	class FormView extends \MyApp\ApplicationController
	{
		function onPageInit(&$page, $args) {
			return;

			$da = \System\Data\DataAdapter::create( 'driver=text;format=TabDelimited;source=' . __ROOT__ . '/app/data/Address Book.csv' );
			$ds = $da->openDataSet();

			$this->page->add( new \System\UI\WebControls\FormView( 'myFormView' ));
			$this->myFormView->dataSource = $ds;
			$this->myFormView->dataBind();
		}
	}
?>