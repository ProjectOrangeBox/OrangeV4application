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
 * Extension to the CodeIgniter Cache Library
 *
 * Adds additional request & export cache libraries
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2019
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 * @filesource
 *
 * @uses event Event
 *
 * @config cache_path `ROOTPATH.'/var/cache/'`
 * @config cache_default `dummy`
 * @config cache_ttl `60`
 *
 */
class Cache
{
	/**
	 * configuration storage
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * $drivers
	 *
	 * @var array
	 */
	protected $drivers = [];

	/**
	 * Reference to the driver
	 *
	 * @var mixed
	 */
	protected $adapter = 'dummy';

	/**
	 * $ttl
	 *
	 * @var integer
	 */
	static protected $ttl = 0;

	/**
	 *
	 * Constructor
	 *
	 * @access public
	 *
	 * @param array $config []
	 *
	 */
	public function __construct(array &$config = [])
	{
		/* combined config */
		$this->config = array_replace(\orange::loadFileConfig('config'),$config);

		if (!isset($this->config['cache_default'])) {
			throw new \Exception('No value set for "cache_default" in config.php');
		}

		$this->adapter = $this->config['cache_default'];

		// If the specified adapter isn't available switch to backup.
		if (!$this->driver($this->adapter)->is_supported()) {
			// Backup isn't supported either. Default to 'Dummy' driver.
			log_message('error', 'Cache adapter "'.$this->adapter.'" is unavailable. Cache is now using "Dummy" adapter.');

			$this->adapter = 'dummy';
		}
	}

	public function __get($name)
	{
		/* if the driver doesn't exist the driver() method will throw a exception */
		return $this->driver($name);
	}

	public function __call($name, $arguments)
	{
		/* test for supported methods */
		if (!in_array($name,['get','save','delete','increment','decrement','clean','cache_info','get_metadata'])) {
			throw new \Exception($name.' is a unsupported method.');
		}

		return call_user_func_array([$this->driver($this->adapter),$name],$arguments);
	}

	// ------------------------------------------------------------------------

	/**
	 * Is the requested driver supported in this environment?
	 *
	 * @param	string	$driver	The driver to test
	 * @return	array
	 */
	public function is_supported($driver)
	{
		return $this->driver($driver)->is_supported();
	}

	protected function driver(string $name) /* mixed */
	{
		if (!isset($this->drivers[$name])) {
			$caches = \orange::fileConfig('services.cache_drivers');

			if (!isset($caches[$name])) {
				throw new \Exception('"'.$name.'" cache server not found.');
			}

			$service = $caches[$name];

			$this->drivers[$name] = new $service($this->config);
		}

		return $this->drivers[$name];
	}

	/**
	 *
	 * Get the current Cache Time to Live with optional "window" support to negate a cache stamped
	 *
	 * @access public
	 *
	 * @param bool $use_window true
	 *
	 * @return int
	 *
	 */
	static public function ttl(/* mixed */ $cache_ttl = null,bool $use_window = true) : int
	{
		if (is_bool($cache_ttl)) {
			$use_window = $cache_ttl;
			$cache_ttl = self::$ttl;
		} elseif (is_null($cache_ttl)) {
			/* get the cache ttl from the config file */
			$cache_ttl = self::$ttl;
		} else {
			$cache_ttl = (int)$cache_ttl;
		}

		/* are they using the window option? */
		if ($use_window) {
			/* let determine the window size based on there cache time to live length no more than 5 minutes */
			$window = min(300, ceil($cache_ttl * .02));
			/* add it to the cache_ttl to get our "new" cache time to live */
			$cache_ttl += mt_rand(-$window, $window);
		}

		return $cache_ttl;
	}

	/**
	 *
	 * Delete cache records based on dot notation "tags"
	 *
	 * @access public
	 *
	 * @param mixed $args array, period separated list of tags or multiple arguments
	 *
	 * @return Cache
	 *
	 * #### Example
	 * ```php
	 * delete_cache_by_tags('acl','user','roles');
	 * delete_cache_by_tags('acl.user.roles');
	 * delete_cache_by_tags(['acl','user','roles']);
	 * ```
	 */
	public function delete_by_tags($args) : Cache
	{
		/* determine if it's a array, period separated list of tags or multiple arguments */
		if (is_array($args)) {
			$tags = $args;
		} elseif (strpos($args, '.') !== false) {
			$tags = explode('.', $args);
		} else {
			$tags = func_get_args();
		}

		/* log a debug entry */
		log_message('debug', 'delete_cache_by_tags '.implode(', ', $tags));

		/* trigger a event incase somebody else needs to know send in our array of tags by reference */
		ci('event')->trigger('delete.cache.by.tags', $tags);

		/* get all of the currently loaded cache driver cache keys */
		$cached_keys = $this->cache_info();

		/* if the cache key has 1 or more matching tag delete the entry */
		if (is_array($cached_keys)) {
			foreach ($cached_keys as $key) {
				if (count(array_intersect(explode('.', $key['name']), $tags))) {
					$this->delete($key['name']);
				}
			}
		}

		return $this;
	}

} /* end class */
