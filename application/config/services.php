<?php
/**
 * Since we are using a service locator pattern this is where the services are loaded.
 * This of course can be overridden to mock services as need
 */

return [

	'auth'=>'\projectorangebox\orange\library\Auth',
	'cache'=>'\projectorangebox\orange\library\Cache',
	'errors'=>'\projectorangebox\orange\library\Errors',
	'event'=>'\projectorangebox\orange\library\Event',
	'page'=>'\projectorangebox\orange\library\Page',
	'validate'=>'\projectorangebox\orange\library\Validate',
	'wallet'=>'\projectorangebox\orange\library\Wallet',
	'session'=>'\projectorangebox\orange\library\Session',

	'config'=>'\projectorangebox\orange\library\Config',
	'log'=>'\projectorangebox\orange\library\Log',
	'router'=>'\projectorangebox\orange\library\Router',
	'output'=>'\projectorangebox\orange\library\Output',
	'input'=>'\projectorangebox\orange\library\Input',

	'o_user_model'=>'\projectorangebox\orange\model\O_user_model',
];
