<?php

namespace projectorangebox\orange\library\cache;

use projectorangebox\orange\library\traits\Cache_DeleteByTag;
use projectorangebox\orange\library\traits\Cache_inline;

/* wrapper */

class Apc extends \CI_Cache_apc
{
	use Cache_DeleteByTag;
	use Cache_inline;

	/**
	 * Cache Info
	 *
	 * @param	string	user/filehits
	 * @return	mixed	array on success, false on failure
	 */
	 public function cache_info($type = 'user')
	 {
		 return apc_cache_info($type);
	 }

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