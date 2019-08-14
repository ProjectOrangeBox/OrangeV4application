<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_table_remember_position extends Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/table_remember_position/table_remember_position'.PAGE_MIN.'.js');
		}
	}
}
