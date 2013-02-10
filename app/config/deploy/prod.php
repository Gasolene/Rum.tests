<?php
	namespace System\Deploy;

	class Prod extends \System\Deploy\DeploymentBase
	{
		public $server="shinbine.com";
                public $port="2222";
		public $user="root";
		public $password="";
		public $home_path="/var/www/html/test";
		public $repository_path="/Users/Darnell/Applications/test";

		public function init()
		{
			$this->run("mkdir {$this->home_path}/releases");
		}

		public function deploy()
		{
			$this->run("mkdir {$this->release_path}");
			$this->put("{$this->repository_path}/app", $this->release_path);

			$this->run("unlink {$this->home_path}/current");
			$this->run("ln -s {$this->release_path} {$this->home_path}/current");
		}
	}
?>