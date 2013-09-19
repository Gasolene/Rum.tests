<?php
    namespace MyApp\Controllers;

	class GridView2 extends \MyApp\ApplicationController {

		function onPageInit($sender, $args) {
			$this->page->add(new \System\Web\WebControls\Form('form'));
			$this->page->form->add(\MyApp\Models\Customer::gridview('table'));
			$this->table->showFilters = true;
			$this->table->showFooter = true;
			$this->page->table->columns->ajaxPostBack = true;
			$this->page->form->table->columns->add(new \System\Web\WebControls\GridViewButton('customer_id', 'Delete', 'action', 'Are you sure you want to delete?', '', '', 'action', 'Add'));

			$this->page->table->columns->ajaxPostBack = true;
		}

		function onPageLoad($sender, $args) {
			$this->table->dataSource = \MyApp\Models\Customer::all();
		}

		public function onTablePost($sender, $args)
		{
			if($this->isAjaxPostBack)
			{
				if(isset(\System\Web\HTTPRequest::$post["customer_id"]))
				{
					$entity = \MyApp\Models\Customer::findById(\System\Web\HTTPRequest::$post["customer_id"]);

					foreach($entity->fields as $field=>$type)
					{
						if(isset(\System\Web\HTTPRequest::$post[$this->formatField($field)]))
						{
							$entity[$field] = \System\Web\HTTPRequest::$post[$field];
						}
					}

					$entity->save();
					\Rum::flash("s:Customer {$entity["customer_id"]} has been updated");
				}
			}
		}

		public function onActionPost($sender, $args)
		{
			if((int)$args["action"])
			{
				// Delete
				$this->table->deleteRow($args["action"]);
				\Rum::flash("s:Record #{$args["action"]} deleted");
			}
			else
			{
				// Insert
				$this->table->insertRow();
				\Rum::flash("s:Customer {$this->table->dataSource["customer_id"]} added");
			}
			\Rum::forward();
		}

		public function onActionAjaxPost($sender, $args)
		{
			if((int)$args["action"])
			{
				// Delete
				$this->table->deleteRow($args["action"]);
				\Rum::flash("s:AJAXRecord #{$args["action"]} deleted");
			}
			else
			{
				// Insert
				$this->table->insertRow();
				\Rum::flash("s:AJAXCustomer {$this->table->dataSource["customer_id"]} added");
			}
			\Rum::forward();
		}
	}
?>