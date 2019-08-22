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
	 * track if the combined cached configuration has been lazy loaded
	 *
	 * @var boolean
	 */
	protected $lazyLoaded = false;

	/**
	 * $hasDatabase
	 *
	 * @var mixed string|bool
	 */
	protected $hasDatabase = false;

	/**
	 * $databaseReady
	 *
	 * @var boolean
	 */
	protected $databaseReady = false;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		if (isset($this->config['database_settings']) && $this->config['database_settings'] !== false) {
			$this->hasDatabase = $this->config['database_settings'];
		}

		log_message('info', 'Orange Config Class Initialized');
	}

	/**
	 * override parent
	 *
	 * Fetch a config file item
	 *
	 * @param	string	$item	Config item name
	 * @param	string	$index	Index name
	 * @return	string|null	The configuration item or NULL if the item doesn't exist
	 */
	public function item($item, $index = '')
	{
		return (\strpos($item,'.') !== false) ? $this->dotNotation($item,(($index === '') ? null : $index)) : parent::item($item,$index);
	}

	/**
	 * override parent
	 *
	 * Set a config file item
	 *
	 * @param	string	$item	Config item key
	 * @param	string	$value	Config item value
	 * @return	void
	 */
	public function set_item($item, $value)
	{
		return (\strpos($item,'.') !== false) ? $this->setDotNotation($item,$value) : parent::set_item($item,$value);
	}

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
	public function dotNotation(string $setting, $default=null)
	{
		log_message('debug', 'Config::dotNotation::'.$setting);

		$this->_lazyLoad();

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
	 * @return Config
	 *
	 */
	public function setDotNotation(string $setting, $value=null) : Config
	{
		log_message('debug', 'Config::setDotNotation::'.$setting);

		$this->_lazyLoad();

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
		log_message('debug', 'Config::flush');

		$this->lazyLoaded = false;

		$cacheDatabaseFilePath = \orange::fileConfig('config.cache_path').'config.database.php';

		if (\file_exists($cacheDatabaseFilePath)) {
			\unlink($cacheDatabaseFilePath);
		}

		$cacheFilePath = \orange::fileConfig('config.cache_path').'config.file.php';

		return (\file_exists($cacheFilePath)) ? \unlink($cacheFilePath) : true;
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
	protected function _lazyLoad() : void
	{
		/* if this has a database model and the database is attached to CI then we can load again this time with the database */
		if ($this->hasDatabase && function_exists('DB')) {
			$this->databaseReady = true;
			$this->lazyLoaded = false;
		}

		if (!$this->lazyLoaded) {
			$fileConfig = $this->_getFileConfig();

			if ($this->databaseReady) {
				$databaseConfig = $this->_getDatabaseConfig();
			} else {
				$databaseConfig = [];
			}

			$this->lazyLoaded = true;

			$this->config = \array_replace($fileConfig,$databaseConfig);
		}
	}

	protected function _getFileConfig() : array
	{
		$fileConfig = [];

		$cacheFilePath = \orange::fileConfig('config.cache_path').'config.file.php';

		if (ENVIRONMENT == 'development' || !file_exists($cacheFilePath)) {
			/**
			 * The application config folder has 1 of every
			 * known config file so using this and a combination of
			 * loadConfig we can as load the environmental
			 * configuration files
			 */
			foreach (glob(APPPATH.'/config/*.php') as $filepath) {
				$basename = basename($filepath, '.php');

				$config = \orange::loadFileConfig($basename);

				if (is_array($config)) {
					foreach ($config as $key=>$value) {
						$fileConfig[$this->_normalizeSection($basename)][$this->_normalizeKey($key)] = $value;
					}
				}
			}

			\orange::var_export_file($cacheFilePath,$fileConfig);
		} else {
			$fileConfig = include $cacheFilePath;
		}

		return $fileConfig;
	}

	protected function _getDatabaseConfig() : array
	{
		$databaseConfig = [];

		$cacheFilePath = \orange::fileConfig('config.cache_path').'config.database.php';

		if (ENVIRONMENT == 'development' || !file_exists($cacheFilePath)) {
			$config = ci($this->hasDatabase)->get_enabled();

			if (is_array($config)) {
				foreach ($config as $record) {
					$databaseConfig[$this->_normalizeSection($record->group)][$this->_normalizeKey($record->name)] = convert_to_real($record->value);
				}
			}

			\orange::var_export_file($cacheFilePath,$databaseConfig);
		} else {
			$databaseConfig = include $cacheFilePath;
		}

		return $databaseConfig;
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
