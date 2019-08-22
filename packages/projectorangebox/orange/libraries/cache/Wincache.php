<?php

namespace projectorangebox\orange\library\cache;

/* wrapper */

class Wincache extends \CI_Cache_wincache
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
		$keys = [];

		/* !!todo */

		return $keys;
	}

} /* end class */