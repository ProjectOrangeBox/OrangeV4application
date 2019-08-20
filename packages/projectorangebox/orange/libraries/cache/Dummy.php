<?php

namespace projectorangebox\orange\library\cache;

use projectorangebox\orange\library\traits\Cache_DeleteByTag;
use projectorangebox\orange\library\traits\Cache_inline;

/* wrapper */

class Dummy extends \CI_Cache_dummy
{
	use Cache_DeleteByTag;
	use Cache_inline;

	/**
	 * cache_keys
	 *
	 * @return array
	 */
	public function cache_keys() : array
	{
		return [];
	}

	/**
	 * ttl
	 *
	 * @param mixed int
	 * @return void
	 */
	public function ttl(int $ttl = null) : int
	{
		return 0;
	}

} /* end class */