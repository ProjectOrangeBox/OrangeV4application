<?php

/* These used by the core and there are required global functions */

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
 *
 * Low Level configuration file loader
 * this does NOT include any database configurations
 * this is used for straight up returned config arrays
 *
 * @param string $name filename
 *
 * @return array
 *
 */
if (!function_exists('loadConfigArray'))
{
	function loadConfigArray(string $name) : array
	{
		$name = strtolower($name);

		$applicationConfig = $envConfig = [];

		if (file_exists(APPPATH.'config/'.$name.'.php')) {
			$applicationConfig = require APPPATH.'config/'.$name.'.php';
		}

		if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/'.$name.'.php')) {
			$envConfig = require APPPATH.'config/'.ENVIRONMENT.'/'.$name.'.php';
		}

		return $applicationConfig + $envConfig;
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
