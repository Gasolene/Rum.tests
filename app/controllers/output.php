<?php
    namespace MyApp\Controllers;

	class Output extends \MyApp\ApplicationController
	{
		function onPageInit( $sender, $args )
		{
			$this->page->add( new \System\Web\WebControls\Output( 'my_output' ));
		}

		function onPageLoad( $sender, $args )
		{
			$this->my_output->dataField = 'customer_name';
//			$this->my_output->value = 5;
			$this->my_output->bind(\MyApp\Models\Customer::findById(13));
		}
	}
?>