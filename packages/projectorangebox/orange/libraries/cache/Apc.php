<?php

namespace projectorangebox\orange\library\cache;

use projectorangebox\orange\library\traits\Cache_DeleteByTag;
use projectorangebox\orange\library\traits\Cache_inline;
use projectorangebox\orange\library\traits\Cache_ttl;

/* wrapper */

class Apc extends \CI_Cache_apc
{
	use Cache_DeleteByTag;
	use Cache_inline;
	use Cache_ttl;


	/**
	 * cache_info
	 *
	 * @param mixed $type
	 * @return void
	 */
	public function cache_info($type = 'user')
	{
		return apc_cache_info($type);
	}

	/**
	 * cache_keys
	 *
	 * @return void
	 */
	public function cache_keys() : array
	{
		$keys = [];

		$info = apc_cache_info('user');

		foreach ($info['cache_list'] as $cache_list) {
			$keys[] = $cache_list['info'];
		}

		return $keys;
	}

} /* end class */