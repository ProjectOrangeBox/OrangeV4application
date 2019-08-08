<?php

/**
 * ci
 *
 * @param mixed string service name to load
 * @param mixed string when attaching it to the CodeIgniter super object attach it as
 * @return mixed
 */
if (!function_exists('ci'))
{
	function ci(string $name = null, string $as = null) /* mixed */
	{
		/* get a instance of CodeIgniter */
		$instance = get_instance();

		/* if the name has segments (namespaced or folder based) we only need the last which is the service name */
		$serviceName = ($as) ?? basename(str_replace('\\','/',$name),'.php');

		if ($serviceName) {
			/* has this service been attached yet? */
			if (!isset($instance->$serviceName)) {
				/* is it a named service? */
				$config = loadConfig('services');

				if (isset($config['services'][$name])) {
					$name = $config['services'][$name];
				}

				/* try to let composer autoload load it */
				if (class_exists($name,true)) {
					/* load a matching config if it exists */
					/* create a new instance and attach the singleton to the CodeIgniter super object */
					$instance->$serviceName = new $name(loadConfig($serviceName));
				} else {
					/* else try to let CodeIgniter load it the old fashion way */
					if (substr($name,-6) == '_model') {
						$instance->load->model($name,$serviceName);
					} else {
						/* library will take a config so let's try to find it if it exists */
						$instance->load->library($name,loadConfig($serviceName));
					}
				}
			}

			/* now grab the reference */
			$instance = $instance->$serviceName;
		}

		return $instance;
	}
}

/* override the CodeIgniter loader to use composer */
if (!function_exists('load_class'))
{
	function &load_class(string $class)
	{
		static $_classes = [];

		if (isset($_classes[$class])) {
			return $_classes[$class];
		}

		$name = false;
		$ci_prefix = 'CI_';
		$subclass_prefix = config_item('subclass_prefix');

		if (class_exists($subclass_prefix.ucfirst($class),true)) {
			$name = $subclass_prefix.ucfirst($class);
		} elseif (class_exists($ci_prefix.ucfirst($class),true)) {
			$name = $ci_prefix.ucfirst($class);
		}

		if (!$name) {
			set_status_header(503);
			throw new \Exception('Unable to locate the specified class: "'.$class.'.php"');
		}

		is_loaded($class);

		$_classes[$class] = new $name();

		return $_classes[$class];
	}
}

/**
 * Write a string to a file with atomic uninterruptible
 *
 * @param string $filepath path to the file where to write the data
 * @param mixed $content the data to write
 *
 * @return int the number of bytes that were written to the file.
 */
if (!function_exists('atomic_file_put_contents'))
{
	function atomic_file_put_contents(string $filepath, $content) : int
	{
		/* get the path where you want to save this file so we can put our file in the same file */
		$dirname = dirname($filepath);

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
		$bytes = file_put_contents($tmpfname, $content);

		/* did we write anything? */
		if ($bytes === false) {
			throw new \Exception('atomic file put contents could not file put contents');
		}

		/* changes file permissions so I can read/write and everyone else read */
		if (chmod($tmpfname, 0644) === false) {
			throw new \Exception('atomic file put contents could not change file mode');
		}

		/* move it into place - this is the atomic function */
		if (rename($tmpfname, $filepath) === false) {
			throw new \Exception('atomic file put contents could not make atomic switch');
		}

		/* if it's cached we need to flush it out so the old one isn't loaded */
		remove_php_file_from_opcache($filepath);

		/* if log message function is loaded at this point log a debug entry */
		if (function_exists('log_message')) {
			log_message('debug', 'atomic_file_put_contents wrote '.$filepath.' '.$bytes.' bytes');
		}

		/* return the number of bytes written */
		return $bytes;
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
	function remove_php_file_from_opcache(string $filepath) : bool
	{
		$success = true;

		/* flush from the cache */
		if (function_exists('opcache_invalidate')) {
			$success = opcache_invalidate($filepath, true);
		} elseif (function_exists('apc_delete_file')) {
			$success = apc_delete_file($filepath);
		}

		return $success;
	}
}

/**
 * The most Basic MVC View loader
 *
 * @param string $_view view filename
 * @param array $_data list of view variables
 *
 * @throws \Exception
 *
 * @return string
 *
 * @example $html = view('admin/users/show',['name'=>'Johnny Appleseed']);
 *
 */
if (!function_exists('view'))
{
	function view(string $__view, array $__data=[]) : string
	{
		/* import variables into the current symbol table from an only prefix invalid/numeric variable names with _ 	*/
		extract($__data, EXTR_PREFIX_INVALID, '_');

		/* turn on output buffering */
		ob_start();

		/* bring in the view file */
		include findView($__view);

		/* return the current buffer contents and delete current output buffer */
		return ob_get_clean();
	}
}

/**
 * echo findView('folder/controller/method');
 */
if (!function_exists('findView'))
{
	function findView(string $path) : string
	{
		$cacheFilePath = configKey('cache_path').'/views.php';

		if (ENVIRONMENT == 'development' || !file_exists($cacheFilePath)) {
			$autoload['packages'] = [];

			include APPPATH.'/config/autoload.php';

			$packages = $autoload['packages'];

			$packages[] = 'application';

			$found = [];

			foreach ($packages as $package) {
				$files = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__ROOT__.'/'.$package.'/views')),'#^('.__ROOT__.'/)(.*)/views/(.*)\.php$#Di');

				foreach ($files as $file) {
					$realPath = $file->getRealPath();

					$found[strtolower(substr($realPath,strpos($realPath,'/views/') + 7,-4))] = substr($realPath,strlen(__ROOT__));
				}
			}

			varExportFile($cacheFilePath,$found);
		} else {
			$found = include $cacheFilePath;
		}

		$path = strtolower(trim(str_replace('.php', '', $path),'/'));

		if (!isset($found[$path])) {
			throw new \Exception('Find view could not locate "'.$path.'".');
		}

		return $found[$path];
	}
}

if (!function_exists('varExportFile'))
{
	function varExportFile(string $cacheFilePath, $data) : bool
	{
		if (is_array($data) || is_object($data)) {
			$data = '<?php return '.str_replace('stdClass::__set_state', '(object)', var_export($data, true)).';';
		} elseif (is_scalar($data)) {
			$data = '<?php return "'.str_replace('"', '\"', $data).'";';
		} else {
			throw new \Exception('Cache export save unknown data type.');
		}

		return (bool)atomic_file_put_contents($cacheFilePath, $data);
	}
}

/**
 * Wrapper for getting configure with dot notation
 * ci('config')->dot_item(...)
 *
 * @param string $setting
 * @param mixed $default
 *
 * @throws \Exception
 *
 * @return mixed
 *
 * #### Example
 * ```
 * $foo = config('file.key');
 * $foo = config('file.key2','default value');
 * ```
 */
if (!function_exists('config'))
{
	function config(string $setting,/* mixed */ $default='%%no_value%%') /* mixed */
	{
		$value = ci('config')->dotItem($setting, $default);

		/* only throw an error if nothing found and no default given */
		if ($value === '%%no_value%%') {
			throw new \Exception('The config variable "'.$setting.'" is not set and no default was provided.');
		}

		return $value;
	}
}

/**
 * Wrapper for validation filters
 * This returns the filtered value
 *
 */
if (!function_exists('filter'))
{
	function filter(string $rule, $value)
	{
		/* add filter_ if it's not there */
		foreach (explode('|', $rule) as $r) {
			$a[] = 'filter_'.str_replace('filter_', '', strtolower($r));
		}

		ci('validate')->single(implode('|', $a), $value);

		return $value;
	}
}

/**
 * Wrapper for validate single
 * This return whether there validation
 * passes (true)
 * or fails (false)
 *
 */
if (!function_exists('valid'))
{
	function valid(string $rule, $field) : bool
	{
		ci('validate')->single($rule, $field);

		return (!ci('errors')->has());
	}
}

/**
 * Escape any single quotes with \"
 *
 * @param string $string
 *
 * @return string
 *
 */
if (!function_exists('esc'))
{
	function esc(string $string) : string
	{
		return str_replace('"', '\"', $string);
	}
}

/**
 * Escape html special characters
 *
 * @param $string
 *
 * @return string
 *
 */
if (!function_exists('e'))
{
	function e(string $input) : string
	{
		return (empty($input)) ? '' : html_escape($input);
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
	function env(string $key, $default = null)
	{
		if (!isset($_ENV[$key]) && $default === null) {
			throw new \Exception('The environmental variable "'.$key.'" is not set and no default was provided.');
		}

		return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
	}
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
		return file_put_contents(configKey('log_path').'/orange_debug.log', implode(chr(10), $log).chr(10), FILE_APPEND | LOCK_EX);
	}
}

/**
 * End the current session and store session data.
 * (7.2 returns a boolean but prior it was null)
 * therefore we don't return anything
 *
 * @return void
 *
 */
if (!function_exists('unlockSession'))
{
	function unlockSession() : void
	{
		session_write_close();
	}
}

/**
 * Show output in Browser Console
 *
 * @param mixed $var converted to json
 * @param string $type - browser console log types [log]
 *
 */
if (!function_exists('console'))
{
	function console(/* mixed */ $var, string $type = 'log') : void
	{
		echo '<script type="text/javascript">console.'.$type.'('.json_encode($var).')</script>';
	}
}

/**
 * Try to convert a value to it's real type
 * this is nice for pulling string from a database
 * such as configuration values stored in string format
 *
 * @param string $value
 *
 * @return mixed
 *
 */
if (!function_exists('convertToReal'))
{
	function convert_to_real(string $value)
	{
		/* return on first match multiple exists */
		switch (trim(strtolower($value))) {
		case 'true':
			return true;
			break;
		case 'false':
			return false;
			break;
		case 'empty':
			return '';
			break;
		case 'null':
			return null;
			break;
		default:
			if (is_numeric($value)) {
				return (is_float($value)) ? (float)$value : (int)$value;
			}
		}

		$json = @json_decode($value, true);

		return ($json !== null) ? $json : $value;
	}
}

/**
 * Try to convert a value back into a string
 * this is nice for storing string into a database
 * such as configuration values stored in string format
 *
 * @param mixed $value
 *
 * @return string
 *
 */
if (!function_exists('convertToString'))
{
	function convertToString($value) : string
	{
		/* return on first match multiple exists */

		if (is_array($value)) {
			return str_replace('stdClass::__set_state', '(object)', var_export($value, true));
		}

		if ($value === true) {
			return 'true';
		}

		if ($value === false) {
			return 'false';
		}

		if ($value === null) {
			return 'null';
		}

		return (string) $value;
	}
}

/**
 * This will collapse a array with multiple values into a single key=>value pair
 *
 * @param array $array
 * @param string $key id
 * @param string $value null
 * @param string $sort null
 *
 * @return array
 *
 */
if (!function_exists('simplifyArray'))
{
	function simplifyArray(array $array, string $key = 'id', string $value = null, string $sort = null) : array
	{
		$value = ($value) ? $value : $key;
		$new_array = [];

		foreach ($array as $row) {
			if (is_object($row)) {
				if ($value == '*') {
					$new_array[$row->$key] = $row;
				} else {
					$new_array[$row->$key] = $row->$value;
				}
			} else {
				if ($value == '*') {
					$new_array[$row[$key]] = $row;
				} else {
					$new_array[$row[$key]] = $row[$value];
				}
			}
		}

		switch ($sort) {
			case 'desc':
			case 'd':
				krsort($new_array, SORT_NATURAL | SORT_FLAG_CASE);
			break;
			case 'asc':
			case 'a':
				ksort($new_array, SORT_NATURAL | SORT_FLAG_CASE);
			break;
		}

		return $new_array;
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
 *
 * Low Level configuration file loader
 * this does NOT include any database configurations
 *
 * @param string $name filename
 * @param string $variable array variable name there configuration is stored in [config]
 *
 * @return array
 *
 */
if (!function_exists('loadConfig'))
{
	function loadConfig(string $name, string $variable = 'config') : array
	{
		/* this actually becomes a "global" for this function */
		static $_configLoaded;

		$$variable = [];

		$name = strtolower($name);

		if (!isset($_configLoaded[$name][$variable])) {
			if (file_exists(APPPATH.'config/'.$name.'.php')) {
				require APPPATH.'config/'.$name.'.php';
			}

			if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/'.$name.'.php')) {
				require APPPATH.'config/'.ENVIRONMENT.'/'.$name.'.php';
			}

			$_configLoaded[$name][$variable] = $$variable;
		}

		return $_configLoaded[$name][$variable];
	}
}

/**
 * Low Level configuration value loader
 * Grab a single value from the base config.php configuration file
 * When using this function naturally database entries will not be included
 * because this is to be used before the database is even connected
 *
 * @param string $key
 * @return mixed
 */
if (!function_exists('configKey'))
{
	function configKey(string $key) /* mixed */
	{
		$config = loadConfig('config');

		if (!isset($config[$key])) {
			throw new \Exception('No value set for "'.$key.'" in config.php.');
		}

		return $config[$key];
	}
}

/**
 *
 * Simple view merger
 * replace {tags} with data in the passed data array
 *
 * @access
 *
 * @param string $template
 * @param array $data []
 *
 * @return string
 *
 * #### Example
 * ```
 * $html = quick_merge('Hello {name}',['name'=>'Johnny'])
 * ```
 */
if (!function_exists('quickMerge'))
{
	function quickMerge(string $template, array $data=[]) : string
	{
		if (preg_match_all('/{([^}]+)}/m', $template, $matches)) {
			foreach ($matches[1] as $key) {
				$template = str_replace('{'.$key.'}', $data[$key], $template);
			}
		}

		return $template;
	}
}

require_once BASEPATH.'core/CodeIgniter.php';
