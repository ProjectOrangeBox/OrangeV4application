<?php

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
if (!function_exists('site_url')) {
	function site_url(string $uri = '', bool $protocol = null) : string
	{
		/* Call CodeIgniter version first if it has a protocol if not just use ours */
		if ($protocol !== false) {
			$uri = ci('config')->site_url($uri, $protocol);
		}

		/* where is the cache file? */
		$cacheFilePath = configKey('cache_path').'/site_url.php';

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

/**
 * echo findView('folder/controller/method');
 */
if (!function_exists('findView'))
{
	function findView(string $path) : string
	{
		$views = loadConfigArray('views');

		if (!isset($views[$path])) {
			throw new \Exception('Find view could not locate "'.$path.'".');
		}

		return $views[$path];
	}
}

/* strip __ROOT__ from any path */
if (!function_exists('getAppPath'))
{
	function getAppPath(string $path) : string
	{
		return (substr($path,0,strlen(__ROOT__)) == __ROOT__) ? substr($path,strlen(__ROOT__)) : $path;
	}
}

/* regular expression search packages and application for files */
if (!function_exists('applicationSearch'))
{
	function applicationSearch(string $regex,closure $closure) : array
	{
		$autoload['packages'] = [];

		include APPPATH.'/config/autoload.php';

		$packages = $autoload['packages'];

		$packages[] = 'application';

		$found = [];

		foreach ($packages as $package) {
			$files = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__ROOT__.'/'.$package)),'#^('.__ROOT__.'/)'.$regex.'$#Di');

			foreach ($files as $file) {
				$found += $closure(getAppPath($file->getRealPath()));
			}
		}

		return $found;
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

if (!function_exists('configExportFile'))
{
	function configExportFile(string $configFilePath, $data) : bool
	{
		if (is_array($data) || is_object($data)) {
			$data = '<?php return '.str_replace('stdClass::__set_state', '(object)', var_export($data, true)).';';
		} elseif (is_scalar($data)) {
			$data = '<?php return "'.str_replace('"', '\"', $data).'";';
		} else {
			throw new \Exception('Config export save unknown data type.');
		}

		return (bool)atomic_file_put_contents(APPPATH.'config/'.$configFilePath.'.php', $data);
	}
}
