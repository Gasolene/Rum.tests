<?php
	$_SERVER["APP_ENV"] = "dev";

	define('__TMP_PATH__', '../.tmp');

	ini_set( 'memory_limit', '5264M' );
    ini_set( 'max_execution_time', '300' );
	ini_set( 'apc.enabled', '0' );

	define( '__MY_TEST__', 'foo' );

	ini_set('display_errors', 'on');
	//define('__SHOW_DEPRECATED_NOTICES__', true);
?>