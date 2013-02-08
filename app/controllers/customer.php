<?php
    namespace MyApp\Controllers;
    use \System\UI\WebControls;

	class Customer extends \Scaffolding\ScaffoldingControllerBase implements \Scaffolding\IScaffolding
    {
        protected $scaffold = '\MyApp\Models\Customer';
		protected $assocMapping = true;

		function onPageInit( &$sender, $args ) {
			parent::onPageInit($sender, $args);

            $master = \MyApp\UI::MasterView('master');
            $master->template = \Rum::config()->views . '/_includes/common.tpl';
            $this->page->setMaster($master);

		}
    }
?>