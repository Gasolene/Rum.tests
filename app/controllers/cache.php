<?php
    namespace MyApp\Controllers;

	class Cache extends \MyApp\ApplicationController
	{
		protected $outputCache = 20;

		public function onPageInit()
		{
			if(isset(\System\IO\HTTPRequest::$get["clear"]))
			{
				$this->clearCache();
			}
		}
	}
?>