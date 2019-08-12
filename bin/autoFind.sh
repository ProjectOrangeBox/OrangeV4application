#!/usr/bin/env php
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

define('__ROOT__',realpath('../'));

chdir(__ROOT__);

/* .env file */
if (!file_exists('.env')) {
	echo getcwd().'/.env file missing.';
	exit(1); // EXIT_ERROR
}

/* bring in the system .env files */
$_ENV = array_merge($_ENV,parse_ini_file('.env',true,INI_SCANNER_TYPED));

define('APPPATH',__ROOT__.'/application/');
define('ENVIRONMENT', isset($_ENV['CI_ENV']) ? $_ENV['CI_ENV'] : 'development');

require __ROOT__.'/packages/projectorangebox/orange/libraries/CoreCommon.php';

echo 'Application Root: '.__ROOT__.PHP_EOL;

foreach (['views','filters','validations','pear_plugins'] as $folder) {
	varExportFile(APPPATH.'config/'.$folder.'.php',applicationSearch('(.*)/'.$folder.'/(.*)\.php',null,'/'.$folder.'/'));

	echo PHP_EOL.'*** '.strtoupper(trim($folder,'/\\')).PHP_EOL.PHP_EOL.file_get_contents(APPPATH.'config/'.$folder.'.php').PHP_EOL;
}