<?php

namespace projectorangebox\orange\library\cache;

/* wrapper */

class Dummy extends \CI_Cache_dummy
{
	use DeleteByTag;
	use Inline;
	use Ttl;

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