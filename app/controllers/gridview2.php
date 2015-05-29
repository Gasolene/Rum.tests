<?php
    namespace MyApp\Controllers;

	class GridView2 extends \MyApp\ApplicationController {

		function onPageInit($sender, $args) {
			$this->page->add(\MyApp\Models\Customer::gridview('table'));
			$this->page->table->showFilters = true;
			$this->page->table->showFooter = true;
			$this->page->table->pageSize=4;
			$this->page->table->columns->ajaxPostBack = true;
			$this->page->table->enableViewState = false;
			$this->page->table->columns[0]->valueField = 'category_id';
			$this->page->table->columns[0]->textField = 'category';
			$this->page->table->columns->add(new \System\Web\WebControls\GridViewButton('customer_id', 'Delete', 'action', 'Are you sure you want to delete?', '', '', 'action', 'Add'));
		}

		function onPageLoad($sender, $args) {
			$this->page->table->dataSource = \MyApp\Models\Customer::all();
		}

		public function onPagePost($sender, $args)
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
							if($this->table->columns->findColumn($field)->validate($err))
							{
								$entity[$field] = \System\Web\HTTPRequest::$post[$field];
								$entity->save();
								\Rum::flash("s:Customer {$entity["customer_id"]} has been updated");
							}
							else
							{
								\Rum::flash("f:{$err}");
							}
						}
					}
				}
			}
		}

		public function onActionPost($sender, $args)
		{
			\Rum::flash("Try Action on Record #{$args["action"]}");

			if($args["action"]=="Delete")
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
				if($this->table->validate($err))
				{
					$object = \MyApp\Models\Customer::create();
					$this->table->fill($object);
					$object->save();
					\Rum::flash("s:Customer {$this->table->dataSource["customer_id"]} added");
				}
				else
				{
					\Rum::flash("f:{$err}");
				}
			}
			\Rum::forward();
		}

		public function xonActionAjaxPost($sender, $args)
		{
			\Rum::flash("Try AJAX Delete Record #{$args["customer_id"]}");

			// Ajax Delete
			$this->table->deleteRow($args["customer_id"]);
			\Rum::flash("s:Customer #{$args["customer_id"]} has been deleted");
			$this->table->needsUpdating = true;
		}
	}
?>