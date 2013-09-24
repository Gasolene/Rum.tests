<?php
    namespace MyApp\Controllers;

	class GridView2 extends \MyApp\ApplicationController {

		function onPageInit($sender, $args) {
			$this->page->add(new \System\Web\WebControls\Form('form'));
			$this->page->form->add(\MyApp\Models\Customer::gridview('table'));
			$this->table->showFilters = true;
			$this->table->showFooter = true;
			$this->table->pageSize=4;
			$this->page->table->columns->ajaxPostBack = true;
			$this->page->table->enableViewState = false;
			$this->page->form->table->columns->add(new \System\Web\WebControls\GridViewButton('customer_id', 'Delete', 'action', 'Are you sure you want to delete?', '', '', 'action', 'Add'));
		}

		function onPageLoad($sender, $args) {
			$this->table->dataSource = \MyApp\Models\Customer::all();
		}

		public function onTablePost($sender, $args)
		{
			if($this->isAjaxPostBack)
			{
				\Rum::flash("Try AJAX Post");

				if(isset(\System\Web\HTTPRequest::$post["customer_id"]))
				{
					$entity = \MyApp\Models\Customer::findById(\System\Web\HTTPRequest::$post["customer_id"]);

					foreach($entity->fields as $field=>$type)
					{
						if(isset(\System\Web\HTTPRequest::$post[$field]))
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
			\Rum::flash("Try Action on Record #{$args["action"]}");

			if((int)$args["action"])
			{
				\Rum::flash("Try Delete Action on Record #{$args["action"]}");

				// Delete
				$this->table->deleteRow($args["action"]);
				\Rum::flash("s:Customer #{$args["action"]} deleted");
			}
			else
			{
				\Rum::flash("Try Insert Action");

				// Insert
				$this->table->insertRow();
				\Rum::flash("s:Customer {$this->table->dataSource["customer_id"]} added");
			}
			\Rum::forward();
		}

		public function xonActionAjaxPost($sender, $args)
		{
			\Rum::flash("Try AJAX Delete Record #{$args["customer_id"]}");

			// Ajax Delete
			$this->table->deleteRow($args["customer_id"]);
			\Rum::flash("s:Customer #{$args["customer_id"]} has been deleted");
			$this->table->updateAjax();
		}
	}
?>