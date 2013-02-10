<?php
	namespace Rum\Deploy;

	class Test2 extends \System\Deploy\DeploymentBase
	{
		public $server="samwise";
		public $user="root";
		public $password="";
		public $home_path="/var/www/html/dev.commerx.com/darnell/projects/staging1";
		public $repository_path="C:\Documents and Settings\Darnell\My Documents\NetBeansProjects\AFPj";

		public function deploy()
		{
			$this->run("mkdir {$this->release_path}");
			$this->put("{$this->repository_path}/app", $this->release_path);

			$this->run("unlink {$this->home_path}/current");
			$this->run("ln -s {$this->release_path} {$this->home_path}/current");

			// sym link to logs
			$this->run("ln -s {$this->home_path}/shared/logs {$this->home_path}/current/logs");
		}
	}
?>