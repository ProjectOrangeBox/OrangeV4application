#!/usr/bin/env php
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

define('__ROOT__',realpath('../'));
define('APPPATH',__ROOT__.'/application/');

echo 'Application Root: '.__ROOT__.PHP_EOL.PHP_EOL;
echo '-- Cut & Paste as needed --'.PHP_EOL.PHP_EOL;

require __ROOT__.'/packages/projectorangebox/orange/libraries/core/required.php';
require __ROOT__.'/packages/projectorangebox/orange/libraries/core/helpers.php';

/**
 *
 * @httpPost 'snakes/([a-zA-Z]+)/edit/(\d+)' => 'forder1\folder2\admin\welcome::index'
 *
 * $route['snakes/([a-zA-Z]+)/edit/(\d+)']['post'] = 'forder1\folder2\admin\welcome::index';
 *
 * Auto replace URL with the folder path inside the controllers folder
 * Auto replace Controller with the Directory path based on the ROOT level unless it is in application
 * Auto replace Method with the next found public method
 * @httpGet ~ => *::*
 *
 */

applicationSearch('(.*)/controllers/(.*)\.php',function($realPath) {
	$last = '';
	$lines = file(__ROOT__.$realPath);

	foreach ($lines as $line) {
		if (preg_match_all('%[^\@]+@(http|cli)(\S*) (\S*) => (\S*)%i',$line, $match, PREG_SET_ORDER, 0)) {
			/* found another route */
			println($last);

			$pathinfo = pathinfo($realPath);

			$request = (strtolower($match[0][1]) == 'http') ? strtolower($match[0][2]) : strtolower($match[0][1]);
			$url = $match[0][3];

			if ($url == '~') {
				$controllersFolder = '/controllers/';

				$new = strtolower($pathinfo['dirname'].'/'.$pathinfo['filename']);

				if (substr($new,-10) == 'controller') {
					$new = substr($new,0,-10);
				}

				$url = substr($new,strpos($new,$controllersFolder) + strlen($controllersFolder));
			}

			$controller = $match[0][4];

			$directory = ($pathinfo['dirname'] != '/application/controllers') ? $pathinfo['dirname'].'/'.$pathinfo['filename'] : $pathinfo['filename'];

			$controller = str_replace('*:',str_replace('/','\\',$directory).':',$controller);

			$last = format($request,$url,$controller);
		}

		if (preg_match('%(\s*)public(\s*)function(\s*)([a-z0-9]*)%i', $line, $match, PREG_OFFSET_CAPTURE, 0)) {
			$last = str_replace('::*','::'.$match[4][0],$last);
		}
	}

	/* do we have a route still in there? */
	println($last);
});

echo PHP_EOL;

function format($request,$url,$controller) {
	return ($request == 'get') ? sprintf("\$route['%s'] = '%s';",$url,$controller) : sprintf("\$route['%s']['%s'] = '%s';",$url,$request,$controller);
}

function println(&$last) {
	if (!empty($last)) {
		echo $last.PHP_EOL;

		$last = '';
	}
}