<?php
    namespace MyApp\Controllers;
    use \MyApp\App;

	class Lookup extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {
			

			$this->page->add( new \System\UI\WebControls\Form( 'form' ));
			$this->form->add( new \SuggestBox\SuggestBox( 'City_Suggest' ));
			$this->form->add( new \SuggestBox\Lookup( 'City_lookup' ));
			$this->form->add( new \SuggestBox\SuggestBox( 'City_multi_suggest' ));
			$this->form->add( new \SuggestBox\Lookup( 'City_multi_lookup' ));
			$this->form->add( new \System\UI\WebControls\Button( 'save', 'Save' ));

			$this->City_Suggest->listName = 'MyList';

			$this->City_multi_suggest->delimiter = ',';
			//$this->City_multi_suggest->multiline = true;
			//$this->City_multi_suggest->rows = 2;
			$this->City_multi_lookup->delimiter = ',';
			//$this->City_multi_lookup->multiline = true;
			//$this->City_multi_lookup->rows = 2;
		}

		function onPageLoad( &$page, $args ) {

			$da = \System\Data\DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Cities.csv' );
			$ds = $da->openDataSet();

			// sync controls
			$this->City_Suggest->textField  = 'Name of city';
			$this->City_Suggest->dataSource = $ds;

			$ds->first();
			$this->City_lookup->textField  = 'Name of city';
			$this->City_lookup->valueField  = 'code';
			$this->City_lookup->dataSource = $ds;

			// multi control
			$ds->first();
			$this->City_multi_suggest->textField  = 'Name of city';
			$this->City_multi_suggest->dataSource = $ds;

			$ds->first();
			$this->City_multi_lookup->textField  = 'Name of city';
			$this->City_multi_lookup->valueField  = 'code';
			$this->City_multi_lookup->dataSource = $ds;
		}

		function onPagePost( &$page, $args ) {
			\Rum::flash( htmlentities( "City_Suggest: {$this->City_Suggest->value}" ));
			\Rum::flash( htmlentities( "City_lookup: {$this->City_lookup->value}" ));
			\Rum::flash( htmlentities( "City_multi_suggest: {$this->City_multi_suggest->value}" ));
			\Rum::flash( htmlentities( "City_multi_lookup: ".serialize($this->City_multi_lookup->value)."" ));
		}
	}
?>