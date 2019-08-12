<?php

namespace projectorangebox\orange\library;

/**
 * Orange
 *
 * An open source extensions for CodeIgniter 3.x
 *
 * This content is released under the MIT License (MIT)
 * Copyright (c) 2014 - 2019, Project Orange Box
 */

/**
 * Extension to CodeIgniter Config Class
 *
 * `dot_item_lookup($keyvalue,$default)` lookup configuration using dot notation with optional default
 *
 * `set_dot_item($name,$value)` set non permanent value in config
 *
 * `flush()` flush the cached configuration
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2019
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 * @filesource
 *
 * @uses # o_setting_model - Orange Settings Model
 * @uses # export cache - Orange Export Cache
 * @uses # load_config() - Orange Config File Loader
 * @uses # convert_to_real() - Orange convert string values into PHP real values where possible
 *
 * @config no_database_settings boolean
 *
 */

class Config extends \CI_Config
{
	/**
	 * track if the combined cached configuration has been loaded
	 *
	 * @var boolean
	 */
	protected $loaded = false;

	/**
	 *
	 * Provides dot notation selection of configuration values
	 * this is the "recommended" way to make sure you get database values as well
	 *
	 * #### Example
	 * ```php
	 * $value = ci('config')->dot_item('email.protocol','sendmail');
	 * ```
	 * @access public
	 *
	 * @param string $setting filename.key
	 * @param $default null
	 *
	 * @return mixed
	 *
	 */
	public function dotItem(string $setting, $default=null)
	{
		log_message('debug', 'MY_Config::item_dot::'.$setting);

		/* have we loaded the config? */
		$this->_loadConfig();

		$value = $default;
		$section = false;

		if (strpos($setting, '.')) {
			list($file, $key) = explode('.', $setting, 2);
		} else {
			$file = $setting;
			$key = false;
		}

		$file = $this->_normalizeSection($file);

		if (isset($this->config[$file])) {
			$section = $this->config[$file];
		}

		if ($key) {
			$key = $this->_normalizeKey($key);

			if (isset($section[$key])) {
				$value = $section[$key];
			}
		} elseif ($section) {
			$value = $section;
		}

		return $value;
	}

	/**
	 *
	 * Change or Add a dot notation config value
	 * NOT Saved between requests
	 *
	 * @access public
	 *
	 * @param string $setting
	 * @param $value null
	 *
	 * @return MY_Config
	 *
	 */
	public function setDotItem(string $setting, $value=null) : Config
	{
		log_message('debug', 'MY_Config::set_item_dot::'.$setting);

		/* have we loaded the config? */
		$this->_loadConfig();

		list($file, $key) = explode('.', strtolower($setting), 2);

		if ($key) {
			$this->config[$this->_normalizeSection($file)][$this->_normalizeKey($key)] = $value;
		} else {
			$this->config[$this->_normalizeSection($file)] = $value;
		}

		/* allow chaining */
		return $this;
	}

	/**
	 *
	 * Flush the cached data for the NEXT request
	 *
	 * @access public
	 *
	 * @throws
	 * @return bool
	 *
	 */
	public function flush() : bool
	{
		log_message('debug', 'MY_Config::settings_flush');

		$this->loaded = false;

		$cacheFilePath = configFile('config.cache_path').'/config.php';

		return (file_exists($cacheFilePath)) ? unlink($cacheFilePath) : true;
	}

	/**
	 *
	 * Load the combined Application, Environmental, Database Configuration values
	 *
	 * @access protected
	 *
	 * @return void
	 *
	 */
	protected function _loadConfig() : void
	{
		if (!$this->loaded) {
			$this->loaded = true;

			$cacheFilePath = configFile('config.cache_path').'/config.php';

			if (ENVIRONMENT == 'development' || !file_exists($cacheFilePath)) {
				/* no - so we need to build our dynamic configuration */
				$builtConfig = [];

				/* load the application configs */
				foreach (glob(APPPATH.'/config/*.php') as $filepath) {
					$basename = basename($filepath, '.php');

					$config = loadConfig($basename);

					if (is_array($config)) {
						foreach ($config as $key=>$value) {
							$builtConfig[$this->_normalizeSection($basename)][$this->_normalizeKey($key)] = $value;
						}
					}
				}

				/* load the database configs (settings) */
				if (isset($this->config['no_database_settings']) && $this->config['no_database_settings'] != false) {
					$modelName = (is_bool($this->config['no_database_settings'])) ? 'o_setting_model' : $this->config['no_database_settings'];

					$config = ci($modelName)->get_enabled();

					if (is_array($config)) {
						foreach ($config as $record) {
							$builtConfig[$this->_normalizeSection($record->group)][$this->_normalizeKey($record->name)] = convert_to_real($record->value);
						}
					}
				}

				varExportFile($cacheFilePath,array_replace($this->config, $builtConfig));

				$this->config = include $cacheFilePath;
			} else {
				$this->config = include $cacheFilePath;
			}
		}
	}

	protected function _normalizeSection(string $string) : string
	{
		return str_replace(['_','-'], ' ', strtolower($string));
	}

	protected function _normalizeKey(string $string) : string
	{
		return strtolower($string);
	}

} /* end class */
