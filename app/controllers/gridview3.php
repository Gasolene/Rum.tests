<?php
    namespace MyApp\Controllers;

	class GridView3 extends \MyApp\ApplicationController {

		function onPageInit($sender, $args) {
			$this->page->add(\MyApp\Models\Customer::gridview('table'));
			$this->page->table->showFilters = true;
			$this->page->table->showFooter = true;
			$this->page->table->pageSize=4;
			$this->page->table->columns->ajaxPostBack = true;
			$this->page->table->enableViewState = false;
			$this->page->table->columns[0]->valueField = 'category_id';
			$this->page->table->columns[0]->textField = 'category';
		}

		function onPageLoad($sender, $args) {
			//$this->table->dataSource = new \System\Collections\ValueCollection(\MyApp\Models\Customer::all()->rows);
			$this->table->dataSource = \System\DB\DataSet::createFromArray(\MyApp\Models\Customer::all()->rows);
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
	}
?>