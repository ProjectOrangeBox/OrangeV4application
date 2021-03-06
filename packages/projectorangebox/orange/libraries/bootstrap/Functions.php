<?php

/**
 * ci
 *
 * Brand new heavy lifter
 * Supports:
 *
 * $foo = ci('factory',$myconfig,true);
 *
 * $bar = ci('bar',$myconfig);
 * $foobar = ci('foo',$myconfig,'foobar');
 * $fb = ci('fb');
 *
 * $ci = ci();
 *
 */
if (!function_exists('ci'))
{
	function ci(string $name = null,array $userConfig = null,/* string|bool */ $as = null) /* mixed */
	{
		/* Are we looking for a named service? factory or singleton? CodeIgniter "super" object? */
		return ($name) ? ($as === true) ? ci_factory($name,$userConfig) : ci_singleton($name,$userConfig,$as) : get_instance();
	}
}

if (!function_exists('ci_singleton')) {
	function ci_singleton(string $name,array $userConfig = null,string $as = null) {
		$instance = get_instance();

		/* if the name has segments (namespaced or folder based) we only need the last which is the service name */
		$serviceName = strtolower(($as) ?? basename(str_replace('\\','/',$name),'.php'));

		/* has this service been attached yet? */
		if (!isset($instance->$serviceName)) {
			/* try to load it's configuration */
			$serviceConfig = $instance->config->item($serviceName);

			$config = array_replace((array)$serviceConfig,(array)$userConfig);

			/* is it a named service? if it is use the namespaced name instead of the name sent into the function */
			if ($namedService = \orange::findService($name,false)) {
				$name = $namedService;
			}

			/* try to let composer autoload load it */
			if (class_exists($name,true)) {
				/* create a new instance and attach the singleton to the CodeIgniter super object */
				$instance->$serviceName = new $name($config);
			} else {
				/*
				else try to let CodeIgniter load it the old fashion way
				using the _model suffix we can assume it's a model we are trying to load
				*/
				if (substr($name,-6) == '_model') {
					$instance->load->model($name,$serviceName);
				} else {
					/* library will take a config so let's try to find it if it exists */
					$instance->load->library($name,$config);
				}
			}
		}

		/* now grab the reference */
		return $instance->$serviceName;
	}
}

if (!function_exists('ci_factory')) {
	function ci_factory(string $serviceName,array $userConfig = null) {
		$serviceClass = \orange::findService($serviceName,true);

		$serviceConfig = get_instance()->config->item($serviceName);

		$config = array_replace((array)$serviceConfig,(array)$userConfig);

		return new $serviceClass($config);
	}
}

/* override the CodeIgniter loader to use composer and our services send in the file based config array */
if (!function_exists('load_class'))
{
	/*
	 * CodeIgniter Startup Load Order
	 *
	 * Benchmark
	 * Hooks
	 * Config
	 * Log
	 * Utf8
	 * URI
	 * Router
	 * Input
	 * Security
	 * Output
	 * Lang
	 * Loader
	 */
	function &load_class(string $class) : object
	{
		/* exists only in a local function scope */
		static $_classes = [];

		/* has it already been loaded? */
		if (!isset($_classes[$class])) {
			$name = \orange::findService($class,true);

			/**
			 * Tell CI is_loaded function
			 * so these can be attach to the Controller
			 * once it's built
			 * then they can be accessed using $this-> syntax in the controller
			 */
			is_loaded($class);

			$_classes[$class] = new $name;
		}

		return $_classes[$class];
	}
}

/**
 *
 * Orange Assertion Handler
 *
 * @param $file
 * @param $line
 * @param $code
 * @param $desc
 *
 * @return void
 *
 */
if (!function_exists('_assert_handler'))
{
	function _assert_handler($file, $line, $code, $desc='') : void
	{
		/* CLI */
		if (defined('STDIN')) {
			echo json_encode(['file'=>$file,'line'=>$line,'description'=>$desc], JSON_PRETTY_PRINT);

		/* AJAX */
		} elseif (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			echo json_encode(['file'=>$file,'line'=>$line,'description'=>$desc], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);

		/* HTML */
		} else {
			echo '<!doctype html><title>Assertion Failed</title>';
			echo '<style>body, html { text-align: center; padding: 150px; background-color: #492727; font: 20px Helvetica, sans-serif; color: #fff; font-size: 18px;}h1 { font-size: 150%; }article { display: block; text-align: left; width: 650px; margin: 0 auto; }</style>';
			echo '<article><h1>Assertion Failed</h1>	<div><p>File: '.$file.'</p><p>Line: '.$line.'</p><p>Code: '.$code.'</p><p>Description: '.$desc.'</p></div></article>';
		}

		exit(1);
	}
}

/**
 * Write a string to a file with atomic uninterruptible
 *
 * @param string $filename path to the file where to write the data.
 * @param mixed $data The data to write. Can be either a string, an array or a stream resource.
 *
 * @return int This function returns the number of bytes that were written to the file.
 */
if (!function_exists('atomic_file_put_contents'))
{
	function atomic_file_put_contents(string $filename,/* mixed */ $data) : int
	{
		/* get the path where you want to save this file so we can put our file in the same file */
		$dirname = dirname($filename);

		/* is the directory writeable */
		if (!is_writable($dirname)) {
			throw new \Exception('atomic file put contents folder "'.$dirname.'" not writable');
		}

		/* create file with unique file name with prefix */
		$tmpfname = tempnam($dirname, 'afpc_');

		/* did we get a temporary filename */
		if ($tmpfname === false) {
			throw new \Exception('atomic file put contents could not create temp file');
		}

		/* write to the temporary file */
		$bytes = file_put_contents($tmpfname, $data);

		/* did we write anything? */
		if ($bytes === false) {
			throw new \Exception('atomic file put contents could not file put contents');
		}

		/* changes file permissions so I can read/write and everyone else read */
		if (chmod($tmpfname, 0644) === false) {
			throw new \Exception('atomic file put contents could not change file mode');
		}

		/* move it into place - this is the atomic function */
		if (rename($tmpfname, $filename) === false) {
			throw new \Exception('atomic file put contents could not make atomic switch');
		}

		/* if it's cached we need to flush it out so the old one isn't loaded */
		remove_php_file_from_opcache($filename);

		/* if log message function is loaded at this point log a debug entry */
		if (function_exists('log_message')) {
			log_message('debug', 'atomic_file_put_contents wrote '.$filename.' '.$bytes.' bytes');
		}

		/* return the number of bytes written */
		return (int)$bytes;
	}
}

/**
 * invalidate it if it's a cached script
 *
 * @param $fullpath
 *
 * @return
 *
 */
if (!function_exists('remove_php_file_from_opcache'))
{
	function remove_php_file_from_opcache(string $filename) : bool
	{
		$success = true;

		/* flush from the cache */
		if (function_exists('opcache_invalidate')) {
			$success = opcache_invalidate($filename, true);
		} elseif (function_exists('apc_delete_file')) {
			$success = apc_delete_file($filename);
		}

		return (bool)$success;
	}
}

/**
 * get a environmental variable with support for default
 *
 * @param $key string environmental variable you want to load
 * @param $default mixed the default value if environmental variable isn't set
 *
 * @return string
 *
 * @throws \Exception
 *
 * #### Example
 * ```
 * $foo = env('key');
 * $foo = env('key2','default value');
 * ```
 */
if (!function_exists('env'))
{
	function env(string $key, $default = NOVALUE)
	{
		if (!isset($_ENV[$key]) && $default === NOVALUE) {
			throw new \Exception('The environmental variable "'.$key.'" is not set and no default was provided.');
		}

		return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
	}
}

function stripFromStart(string $string,string $strip) : string
{
	return (substr($string,0,strlen($strip)) == $strip) ? substr($string,strlen($strip)) : $string;
}

function stripFromEnd(string $string, string $strip) : string
{
	return (substr($string,-strlen($strip)) == $strip) ? substr($string,0,strlen($string) - strlen($strip)) : $string;
}

/**
 * Simple Logging function for debugging purposes
 * the file name is ALWAYS orange_debug.log
 * and saved in the paths config file log path
 *
 * @params ...mixed
 *
 * @return the number of bytes that were written to the file, or FALSE on failure.
 *
 */
if (!function_exists('l'))
{
	function l()
	{
		/* get the number of arguments passed */
		$args = func_get_args();

		$log[] = date('Y-m-d H:i:s');

		/* loop over the arguments */
		foreach ($args as $idx=>$arg) {
			/* is it's not scalar then convert it to json */
			$log[] = (!is_scalar($arg)) ? chr(9).json_encode($arg) : chr(9).$arg;
		}

		/* write it to the log file */
		return file_put_contents(\orange::getFileConfig('config.log_path').'/orange_debug.log', implode(chr(10), $log).chr(10), FILE_APPEND | LOCK_EX);
	}
}

if (!function_exists('site_url')) {
	/**
	 * site_url
	 * Returns your site URL, as specified in your config file.
	 * also provides auto merging of "merge" fields in {} format
	 *
	 * @param $uri
	 * @param $protocol
	 *
	 * @return
	 *
	 * #### Example
	 * ```
	 * $url = site_url('/{www theme}/assets/css');
	 * ```
	 */
	function site_url(string $uri = '', string $protocol = null) : string
	{
		/* Call CodeIgniter version first if it has a protocol if not just use ours */
		if ($protocol) {
			$uri = ci('config')->site_url($uri, $protocol);
		}

		/* where is the cache file? */
		$cacheFilePath = \orange::getFileConfig('config.cache_path').'site_url.php';

		/* are we in development mode or is the cache file missing */
		if (ENVIRONMENT == 'development' || !file_exists($cacheFilePath)) {
			$array['keys'] = $array['values'] = [];

			$paths = config('paths',null);

			/* build the array for easier access later */
			if (is_array($paths)) {
				foreach ($paths as $find=>$replace) {
					$array['keys'][] = '{'.strtolower($find).'}';
					$array['values'][] = $replace;
				}
			}

			\orange::var_export_file($cacheFilePath,$array);
		} else {
			$array = include $cacheFilePath;
		}

		/* return the merge str replace */
		return str_replace($array['keys'], $array['values'], $uri);
	}
}
