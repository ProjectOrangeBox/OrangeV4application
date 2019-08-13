<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_asset_route extends Pear_plugin
{
	public function __construct()
	{
		$pageConfigs = loadConfigFile('page');

		if (isset($pageConfigs['asset_merge']) && is_array($pageConfigs['asset_merge'])) {
			$uri = str_replace('controller/','/',ci('router')->fetch_route());

			foreach ($pageConfigs['asset_merge'] as $regex=>$closure) {
				if (\is_callable($closure)) {
					if (\preg_match('#^'.$regex.'$#im', $uri)) {
						if ($closure() !== null) {
							/* if something returned do not continue processing */
							break;
						}
					}
				}
			}
		}
	} /* end render */

} /* end class */