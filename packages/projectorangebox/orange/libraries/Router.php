<?php

namespace projectorangebox\orange\library;

class Router {
	/**
	 * Current class name
	 *
	 * @var	string
	 */
	public $class = '';

	/**
	 * Current method name
	 *
	 * @var	string
	 */
	public $method =	'';

	/**
	 * Sub-directory that contains the requested controller class
	 *
	 * @var	string
	 */
	public $directory = '';

	/**
	 * List of routes
	 *
	 * @var	array
	 */
	protected $routes = [];

	/**
	 * $url
	 *
	 * @var string
	 */
	protected $url = '';

	/**
	 * current request method
	 *
	 * @var string
	 */
	protected $requestMethod = '';

	/**
	 * $isXHR
	 *
	 * @var string
	 */
	protected $isXHR = '';

	/**
	 * $rootLevel
	 *
	 * @var undefined
	 */
	protected $rootLevel = '';

	/**
	 * $defaultMethod
	 *
	 * @var undefined
	 */
	protected $defaultMethod = '';

	/**
	 * $processMiddleware
	 *
	 * @var boolean
	 */
	protected $onResponse = true;

	/**
	 * $onRequest
	 *
	 * @var boolean
	 */
	protected $onRequest = true;

	/**
	 * Class constructor
	 *
	 * Runs the route mapping function.
	 *
	 * @param	array	$routing
	 * @return	void
	 */
	public function __construct()
	{
		/* reference to CodeIgniter URI Object */
		$uri = load_class('URI','core');

		$this->url = implode('/', $uri->segments);
		$this->requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

		$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		$isJson = (!empty($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/json') !== false);

		$this->isXHR = ($isAjax || $isJson) ? 'Ajax' : '';

    log_message('debug','Route: HTTP Method:'.$this->requestMethod.' / Is XHR:'.$this->isXHR);

		/* load our routes from the routes configuration file */
		$this->loadConfig();

		/* convert the URL to Controller / Method */
		list($callback,$params) = $this->dispatch($this->getSearch('routes'));

		/* if it's a closure call it */
		if (is_callable($callback)) {
			$callback = call_user_func_array($callback,$params);
		}

    log_message('debug','Route: Call Back: '.$callback);

		$this->setDirectoryClassMethod($callback);

		$uri->rsegments = array_merge([1=>$this->fetch_class(),2=>$this->fetch_method()],$params);

		log_message('debug',__METHOD__);
	}

	/**
	 *
	 * controller::method
	 * /folder/folder/admin/controller::method
	 * \packages\orange\module\controller\folder\folder\admin\controller::method
	 *
	 * $route['welcome/index'] = 'welcome::index';
	 * $route['welcome/index2'] = 'folder1/folder2/admin/welcome::index';
	 * $route['welcome/index3'] = '\packages\orange\module\controllers\folder1\folder2\admin\welcome::index';
	 *
	 */
	protected function setDirectoryClassMethod(string $callback) : void
	{
		if ($callback[0] == '\\') {
			/* root level package controller folder based */
			$segs = explode('\\',$callback);
			$classMethod = array_pop($segs);
			$directory = $this->rootLevel.implode('/',$segs);
		} else {
			/* default CodeIgniter */
			$segs = explode('/',$callback);
			$classMethod = array_pop($segs);
			$directory = implode('/',$segs);
		}

		list($c,$m) = explode('::',$classMethod.'::'.$this->defaultMethod);

		$this->set_directory($directory);
		$this->set_class($c);
		$this->set_method($m);
	}

	/**
	 * loadConfig
	 *
	 * @return void
	 */
	protected function loadConfig() : void
	{
		/* !todo CACHE hum... what about closure routes? https://github.com/brick/varexporter */

		/* where is the cache file? */
		$cacheFilePath = configKey('cache_path').'/routes.php';

		/* are we in development mode or is the cache file missing */
		if (ENVIRONMENT == 'development' || !file_exists($cacheFilePath)) {
			/* we also include some default config values that can be overwritten in route.php config */
			$config['root level'] = '../..';
			$config['default method'] = 'index';
			$config['onRequest'] = true;
			$config['onResponse'] = true;

			/* routes */
			$route = [];

			/* middleware */
			$onRequest = [];
			$onResponse = [];

			$cached = [];

			if (file_exists(APPPATH.'config/routes.php')) {
				include(APPPATH.'config/routes.php');
			}

			if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/routes.php')) {
				include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
			}

			$cached['routes'] = $this->buildArray($route,'routes');
			$cached['request'] = $this->buildArray($onRequest,'onRequest');
			$cached['response'] = $this->buildArray($onResponse,'onResponse');
			$cached['config'] = [
				'rootLevel' => $config['root level'],
				'defaultMethod' => $config['default method'],
				'onResponse' =>$config['onResponse'],
				'onRequest' =>$config['onRequest'],
			];

	    log_message('debug','Route: Build Router Cache File '.$cacheFilePath);

			varExportFile($cacheFilePath,$cached);
		} else {
			$cached = include $cacheFilePath;
		}

		$this->routes['routes'] = $cached['routes'];
		$this->routes['request'] = $cached['request'];
		$this->routes['response'] = $cached['response'];

		$this->rootLevel = $cached['config']['rootLevel'];
		$this->defaultMethod = $cached['config']['defaultMethod'];
		$this->onResponse = $cached['config']['onResponse'];
		$this->onRequest = $cached['config']['onRequest'];
	}

	/**
	 * buildArray
	 *
	 * @param array $routes
	 * @param string $key
	 * @return void
	 */
	protected function buildArray(array $routes,string $key) : array
	{
		$built = [];

		foreach ($routes as $key=>$val) {
			$skip = false;

			if (is_array($val)) {
				$httpMethod = array_keys($val)[0];
				$callback = $val[$httpMethod];
			} else {
				$httpMethod = 'get';
				$callback = $val;
			}

			if (!$skip) {
				$built[strtolower($httpMethod)]['#^'.str_replace(array(':any',':num'), array('[^/]+','[0-9]+'), $key).'$#'] = $callback;
			}
		}

		return $built;
	}

	/**
	 * dispatch
	 * Dispatch the request to the appropriate route(s)
	 *
	 * @param string $uri
	 * @param string $requestMethod
	 * @return array
	 */
	protected function dispatch(array $search) : array
	{
		$matched = [];

		foreach ($search as $regxUrl=>$callback) {
			if (preg_match($regxUrl, $this->url, $params)) {
				/* add custom parameters */
				$params['Ajax'] = $this->isXHR;
				$params['HttpMethod'] = ucfirst($this->requestMethod);

				/* replace arguments with params */
				foreach ($params as $key=>$value) {
					$callback = str_replace('$'.$key,$value,$callback);
				}

				/* shift off the url */
				array_shift($params);

				$matched = [$callback,$params];

				break; /* break out of foreach loop */
			}
		}

		if (empty($matched)) {
			throw new \Exception('No Catch All Route Provided.');
		}

		return $matched;
	}

	/**
	 * getSearch
	 *
	 * @param string $key
	 * @return void
	 */
	protected function getSearch(string $key) : array
	{
		$a = (isset($this->routes[$key][$this->requestMethod]) && is_array($this->routes[$key][$this->requestMethod])) ? $this->routes[$key][$this->requestMethod] : [];
		$b = (isset($this->routes[$key]['*']) && is_array($this->routes[$key]['*'])) ? $this->routes[$key]['*'] : [];

		return $a + $b;
	}

	/**
	 * set_class
	 *
	 * @param string $class
	 * @return void
	 */
	public function set_class(string $class) : void
	{
		$this->class = str_replace('-','_',trim($class,'/'));
	}

	/**
	 * fetch_class
	 *
	 * @return void
	 */
	public function fetch_class()  : string
	{
		return $this->class;
	}

	/**
	 * set_method
	 *
	 * @param string $method
	 * @return void
	 */
	public function set_method(string $method) : void
	{
		$method = str_replace('-','_',trim($method,'/'));

		$this->method = (empty($method)) ? 'index' : $method;
	}

	/**
	 * fetch_method
	 *
	 * @return void
	 */
	public function fetch_method() : string
	{
		return $this->method;
	}

	/**
	 * set_directory
	 *
	 * @param mixed string
	 * @return void
	 */
	public function set_directory(string $directory = null) : void
	{
		$this->directory = (empty($directory)) ? '' : trim($directory,'/').'/';
	}

	/**
	 * fetch_directory
	 *
	 * @return void
	 */
	public function fetch_directory() : string
	{
		return $this->directory;
	}

	/**
	 * onRequest
	 *
	 * @param CI_Input &$input
	 * @return void
	 */
	public function onRequest(\CI_Input &$input) : void
	{
		if ($this->onRequest) {
			$this->on('request',$input);
		}
	}

	/**
	 * onResponse
	 *
	 * @param string &$output
	 * @return void
	 */
	public function onResponse(string &$output) : void
	{
		if ($this->onResponse) {
			$this->on('response',$output);
		}
	}

	/**
	 * on
	 *
	 * @param string $method
	 * @param mixed &$reference
	 * @return void
	 */
	protected function on(string $method, &$reference) : void
	{
		try {
			list($callback,$params) = $this->dispatch($this->getSearch($method));

			foreach ($callback as $classname) {
				if (class_exists($classname,true)) {
					$middleware = new $classname();

					if (method_exists($middleware,$method)) {
						if ($middleware->$method($reference) === false) {
							break; /* break out of foreach */
						}
					}
				} else {
					throw new \Exception('Middleware Class "'.$classname.'" Not Found.');
				}
			}
		} catch (\Exception $e) {
			/**
			 * doesn't matter if no matches found
			 * so disreguard any thrown errors from dispatch method
			 */
		}
	}

} /* end class */
