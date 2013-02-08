<?php
    namespace MyApp\Controllers;
    use \MyApp\App;

	class PickList extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {
			

			$this->page->add( new \System\UI\WebControls\Form( 'form' ));
			$this->form->add( new \PickList\PickList( 'cities' ));
			$this->form->add( \MyApp\UI::Button( 'submit', 'test' ));
		}

		function onPageLoad( &$page, $args ) {
			

			$da = \System\Data\DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Cities.csv' );
			$ds = $da->openDataSet();

			$this->cities->textField  = 'Name of city';
			$this->cities->valueField  = 'code';
			$this->cities->dataSource = $ds;
		}

		function onPost( &$page, $args ) {
			
		}
	}
?>