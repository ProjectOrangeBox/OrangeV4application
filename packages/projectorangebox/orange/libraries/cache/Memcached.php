<?php

namespace projectorangebox\orange\library\cache;

use projectorangebox\orange\library\traits\Cache_DeleteByTag;
use projectorangebox\orange\library\traits\Cache_inline;
use projectorangebox\orange\library\traits\Cache_ttl;

/* wrapper */

class Memcached extends \CI_Cache_memcached
{
	use Cache_DeleteByTag;
	use Cache_inline;
	use Cache_ttl;

	/**
	 * cache_keys
	 *
	 * @return array
	 */
	public function cache_keys() : array
	{
		$keys = [];

		/* !!todo */

		return $keys;
	}

} /* end class */