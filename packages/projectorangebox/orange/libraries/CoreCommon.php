<?php
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
 *
 * Low Level configuration file loader
 * this does NOT include any database configurations
 *
 * @param string $filename filename
 * @param string $variable array variable name there configuration is stored in [config]
 *
 * @return array
 *
 */
if (!function_exists('loadConfigFile'))
{
	function loadConfigFile(string $filename, string $variableVariable = 'config') : array
	{
		/* exists only in a local function scope */
		static $_configLoaded = [];

		$filename = strtolower($filename);

		if (!isset($_configLoaded[$filename])) {
			/* they either return something or use the CI default $config['...'] format so set those up as empty */
			$returnedApplicationConfig = $returnedEnvironmentConfig = $$variableVariable = [];

			if (file_exists(APPPATH.'config/'.$filename.'.php')) {
				$returnedApplicationConfig = require APPPATH.'config/'.$filename.'.php';
			}

			if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/'.$filename.'.php')) {
				$returnedEnvironmentConfig = require APPPATH.'config/'.ENVIRONMENT.'/'.$filename.'.php';
			}

			$_configLoaded[$filename] = (array)$returnedEnvironmentConfig + (array)$returnedApplicationConfig +  (array)$$variableVariable;
		}

		return $_configLoaded[$filename];
	}
}

/**
 *
 * configFile
 *
 * @param string $dotNotation - config filename
 * @param mixed return value - if none giving it will throw an error if the array key doesn't exist
 * @return mixed - based on $default value
 *
 * $view = configFile('view.folder/controller/method');
 *
 * $service = configFile('service.myservice',false);
 *
 */
if (!function_exists('configFile'))
{
	function configFile(string $dotNotation, $default = NOVALUE) /* mixed */
	{
		list($filename,$key) = explode('.',strtolower($dotNotation),2);

		$array = loadConfigFile($filename);

		if (!isset($array[$key]) && $default === NOVALUE) {
			throw new \Exception('Find Config Key could not locate "'.$key.'" in "'.$filename.'".');
		}

		return (isset($array[$key])) ? $array[$key] : $default;
	}
}

if (!function_exists('varExportFile'))
{
	function varExportFile(string $cacheFilePath, $data) : bool
	{
		if (is_array($data) || is_object($data)) {
			$data = '<?php return '.str_replace(['Closure::__set_state','stdClass::__set_state'], '(object)', var_export($data, true)).';';
		} elseif (is_scalar($data)) {
			$data = '<?php return "'.str_replace('"', '\"', $data).'";';
		} else {
			throw new \Exception('Cache export save unknown data type.');
		}

		return (bool)atomic_file_put_contents($cacheFilePath, $data);
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

/* wrapper / helpers */

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
	function view(string $__view, array $__data = []) : string
	{
		/* import variables into the current symbol table from an only prefix invalid/numeric variable names with _ 	*/
		extract($__data, EXTR_PREFIX_INVALID, '_');

		/* turn on output buffering */
		ob_start();

		/* bring in the view file */
		include __ROOT__.configFile('services.#'.$__view);

		/* return the current buffer contents and delete current output buffer */
		return ob_get_clean();
	}
}

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
if (!function_exists('getPath')) {
	function getPath(string $uri = '', string $protocol = null) : string
	{
		/* Call CodeIgniter version first if it has a protocol if not just use ours */
		if ($protocol) {
			$uri = ci('config')->site_url($uri, $protocol);
		}

		/* where is the cache file? */
		$cacheFilePath = configFile('config.cache_path').'/site_url.php';

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

			varExportFile($cacheFilePath,$array);
		} else {
			$array = include $cacheFilePath;
		}

		/* return the merge str replace */
		return str_replace($array['keys'], $array['values'], $uri);
	}
}

/* strip __ROOT__ from any path */
if (!function_exists('getAppPath'))
{
	function getAppPath(string $path) : string
	{
		return stripFromStart($path,__ROOT__);
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

/* regular expression search packages and application for files */
if (!function_exists('applicationSearch'))
{
	function applicationSearch(string $regex) : array
	{
		$config = loadConfigFile('autoload','autoload');

		/* add application as package */
		$config['packages'][] = 'application';

		$found = [];

		foreach ($config['packages'] as $package) {
			$files = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__ROOT__.'/'.$package)),'#^('.__ROOT__.'/)'.$regex.'$#Di');

			foreach ($files as $file) {
				$found[getAppPath($file->getRealPath())] = true;
			}
		}

		/* return just a numbered array */
		return array_keys($found);
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
		return file_put_contents(configFile('config.log_path').'/orange_debug.log', implode(chr(10), $log).chr(10), FILE_APPEND | LOCK_EX);
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
	function convertToReal(string $value) /* mixed */
	{
		$converted = $value;

		switch (trim(strtolower($value))) {
		case 'true':
			$converted = true;
			break;
		case 'false':
			$converted = false;
			break;
		case 'empty':
			$converted = '';
			break;
		case 'null':
			$converted = null;
			break;
		default:
			if (is_numeric($value)) {
				$converted = (is_float($value)) ? (float)$value : (int)$value;
			} else {
				/* if it's json this will return something other than null */
				$json = @json_decode($value, true);

				$converted = ($json !== null) ? $json : $value;
			}
		}

		return $converted;
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
		$converted = $value;

		if (is_array($value)) {
			return str_replace('stdClass::__set_state', '(object)', var_export($value, true));
		} elseif ($value === true) {
			$converted = 'true';
		} elseif ($value === false) {
			$converted = 'false';
		} elseif ($value === null) {
			$converted = 'null';
		} else {
			$converted = (string) $value;
		}

		return $converted;
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

		$simplifiedArray = [];

		foreach ($array as $row) {
			if (is_object($row)) {
				if ($value == '*') {
					$simplifiedArray[$row->$key] = $row;
				} else {
					$simplifiedArray[$row->$key] = $row->$value;
				}
			} else {
				if ($value == '*') {
					$simplifiedArray[$row[$key]] = $row;
				} else {
					$simplifiedArray[$row[$key]] = $row[$value];
				}
			}
		}

		$sort_flags = SORT_NATURAL | SORT_FLAG_CASE;

		switch ($sort) {
			case 'desc':
			case 'd':
			case 'krsort':
				krsort($simplifiedArray, $sort_flags);
			break;
			case 'asc':
			case 'a':
			case 'ksort':
				ksort($simplifiedArray, $sort_flags);
			break;
			case 'sort':
			case 'asort':
				asort($simplifiedArray, $sort_flags);
			break;
			case 'arsort':
			case 'rsort':
				arsort($simplifiedArray, $sort_flags);
			break;
		}

		return $simplifiedArray;
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
	function quickMerge(string $template, array $data = []) : string
	{
		if (preg_match_all('/{([^}]+)}/m', $template, $matches)) {
			foreach ($matches[1] as $key) {
				$template = str_replace('{'.$key.'}', $data[$key], $template);
			}
		}

		return $template;
	}
}
