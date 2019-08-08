<?php

/*
$config['named'] = [
	'auth'=>'\projectorangebox\orange\library\Auth',
	'cache'=>'\projectorangebox\orange\library\Cache',
	'errors'=>'\projectorangebox\orange\library\Errors',
	'event'=>'\projectorangebox\orange\library\Event',
	'page'=>'\projectorangebox\orange\library\Page',
	'validate'=>'\projectorangebox\orange\library\Validate',
	'wallet'=>'\projectorangebox\orange\library\Wallet',
	'session'=>'\projectorangebox\orange\library\Session',

	'o_user_model'=>'\projectorangebox\orange\model\O_user_model',
];
*/

$config['named'] = [
	'auth'=> function($ci) {
		return new \projectorangebox\orange\library\Auth($ci->config->dotItem('auth'),$ci);
	},
	'example'=> function($ci) {
		return new \projectorangebox\orange\library\Example($ci);
	},

];