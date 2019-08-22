<?php

namespace projectorangebox\orange\library\cache;

/* wrapper */

class File extends \CI_Cache_file
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

		foreach (glob($this->_cache_path.'*') as $path) {
			$keys[] = basename($path);
		}

		return $keys;
	}

} /* end class */