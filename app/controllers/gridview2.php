<?php
    namespace MyApp\Controllers;

	class GridView2 extends \MyApp\ApplicationController {

		function onPageInit($sender, $args) {
			$this->page->add(\MyApp\Models\Customer::gridview('table'));
			$this->table->showFilters = true;
			$this->table->showFooter = true;
		}

		function onPageLoad($sender, $args) {
			$this->table->dataSource = \MyApp\Models\Customer::all();
		}
	}
?>