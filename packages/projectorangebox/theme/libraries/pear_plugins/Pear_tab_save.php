<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_tab_save extends Pear_plugin
{
	public function __construct()
	{
		/* provides saving the last selected tab for a give html page */
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/orange-tab-save/orange-tab-save'.PAGE_MIN.'.js');
		}
	}
}
