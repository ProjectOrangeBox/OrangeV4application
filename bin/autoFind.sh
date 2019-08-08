#!/usr/bin/env php
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

define('__ROOT__',realpath('../'));
define('APPPATH',__ROOT__.'/application/');

echo 'Application Root: '.__ROOT__.PHP_EOL;

require __ROOT__.'/packages/projectorangebox/orange/libraries/core/required.php';
require __ROOT__.'/packages/projectorangebox/orange/libraries/core/helpers.php';

configExportFile('views',applicationSearch('(.*)/views/(.*)\.php',function($realPath) {
	$folder = '/views/';
	$ext = '.php';

	echo $realPath.PHP_EOL;

	return [strtolower(substr($realPath,strpos($realPath,$folder) + strlen($folder),-strlen($ext))) => getAppPath($realPath)];
}));

configExportFile('filters',applicationSearch('(.*)/filters/(.*)\.php',function($realPath) {
	$folder = '/filters/';
	$ext = '.php';

	echo $realPath.PHP_EOL;

	return [strtolower(substr($realPath,strpos($realPath,$folder) + strlen($folder),-strlen($ext))) => getAppPath($realPath)];
}));

configExportFile('validations',applicationSearch('(.*)/validations/(.*)\.php',function($realPath) {
	$folder = '/validations/';
	$ext = '.php';

	echo $realPath.PHP_EOL;

	return [strtolower(substr($realPath,strpos($realPath,$folder) + strlen($folder),-strlen($ext))) => getAppPath($realPath)];
}));

configExportFile('pear_plugins',applicationSearch('(.*)/pear_plugins/(.*)\.php',function($realPath) {
	$folder = '/pear_plugins/';
	$ext = '.php';

	echo $realPath.PHP_EOL;

	return [strtolower(substr($realPath,strpos($realPath,$folder) + strlen($folder),-strlen($ext))) => getAppPath($realPath)];
}));