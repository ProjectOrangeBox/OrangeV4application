<?php

/**
 * Routes
 *
 * These are setup using PHPs static Function Syntax
 * MyClass::test();
 *
 */

$route['form/input'] = 'formController::index';
$route['form/output']['post'] = 'formController::post';
$route['form/dn'] = 'formController::dotnotation';

$route['user/drpepper'] = 'don::index';

$route['tyson/([a-zA-Z]+)/edit/(\d+)'] = '\test2\levela\levelb\admin\don\controller::method';
$route['foo/([a-zA-Z]+)/edit/(\d+)'] = '\test2\levela\levelb\admin\don\index\controller::method';
$route['bar/([a-zA-Z]+)/edit/(\d+)'] = '\test2\levela\levelb\admin/don\index\controller::method';

$route['snakes/([a-zA-Z]+)/edit/(\d+)']['Get'] = 'forder1\folder2\admin\welcome::index';

$route['products/(:num)']['Delete'] = 'product/delete/$1';
$route['product/(:num)'] = 'product/edit/$1';
$route['products/delete/(:num)'] = 'product/edit$Httpmethod/$1';

$route['welcome/index'] = 'welcomeController::index';
$route['welcome/index2'] = '\packages\projectorangebox\theme\controllers\folder1\folder2\admin\welcomeController::index';
$route['welcome/index3'] = '\packages\projectorangebox\theme\controllers\folder1\folder2\admin\welcomeController::index';

$route['welcome/remap'] = '\packages\projectorangebox\theme\controllers\welcomeController::remap';

$route['welcome/test'] = 'welcome::test';

$route['']['*'] = '\packages\projectorangebox\theme\controllers\WelcomeController::index';
$route['(.*)']['*'] = '\packages\projectorangebox\theme\controllers\WelcomeController::fourohfour';

/**
 * Middleware Request
 * Method is always request(\CI_Input &$input) : bool
 */

$onRequest['welcome/(.*)']['*'] = ['\packages\projectorangebox\theme\middleware\PrivateMiddleware','\packages\orange\theme\middleware\PublicMiddleware'];

//$onRequest['(.*)']['*'] = ['name'];

$onRequest['(.*)']['post'] = ['packagePath/name'];


/**
 * Middleware Response
 * method is always response(string &$output) : bool
 */

$onResponse['(.*)']['*'] = ['\packages\projectorangebox\theme\middleware\PublicMiddleware'];

/*

These aren't used anymore

$route['default_controller'] = 'welcome';

now should be

$route['']['*'] = 'welcome::index';

and

$route['404_override'] = '';

now should be

$route['(.*)']['*'] = 'welcome::fourohfour';

This isn't used because ALL dashes should be underscores.
If you need dashes then create a route for it.

$route['translate_uri_dashes'] = FALSE;
*/

$config['root level'] = '../..';
$config['default method'] = 'index';
$config['onRequest'] = true;
$config['onResponse'] = true;