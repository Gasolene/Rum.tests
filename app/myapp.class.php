<?php
	namespace MyApp;
	use System\Web\WebApplicationBase;
    ini_set( 'max_execution_time', '300' );

	define('__TMP_PATH__', __ROOT__ . '/.tmp');
	require __ROOT__ . '/app/backwards-compatability.inc.php';
	//ini_set( 'memory_limit', '600M' );

	class MyApp extends WebApplicationBase
	{
		public function onMyAppRun()
		{
			//dmp('XXX');
		}
	}
?>