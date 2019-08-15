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

} /* end class */