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
	protected $lazy_loaded = false;

	public function __construct()
	{
		parent::__construct();

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

		$this->lazy_load();

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

		$this->lazy_load();

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

		$this->lazy_loaded = false;

		$cacheFilePath = \orange::fileConfig('config.cache_path').'config.php';

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
	protected function lazy_load() : void
	{
		if (!$this->lazy_loaded) {
			$cacheFilePath = \orange::fileConfig('config.cache_path').'config.php';

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
							$this->config[$this->_normalizeSection($basename)][$this->_normalizeKey($key)] = $value;
						}
					}
				}

				/* load the database configs (settings) if the value is set in  */
				if (isset($this->config['database_settings']) && $this->config['database_settings'] !== false) {
					$modelName = (is_bool($this->config['database_settings'])) ? 'o_setting_model' : $this->config['database_settings'];

					$config = ci($modelName)->get_enabled();

					if (is_array($config)) {
						foreach ($config as $record) {
							$this->config[$this->_normalizeSection($record->group)][$this->_normalizeKey($record->name)] = convert_to_real($record->value);
						}
					}
				}

				\orange::var_export_file($cacheFilePath,$this->config);

				$this->lazy_loaded = true;
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
