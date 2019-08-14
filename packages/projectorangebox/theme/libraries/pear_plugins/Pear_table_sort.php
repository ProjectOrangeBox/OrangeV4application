<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_table_sort extends Pear_plugin
{
	public function __construct()
	{
		if (config('page.usingCDNs')) {
			ci('page')->js('//cdnjs.cloudflare.com/ajax/libs/tinysort/3.1.4/tinysort.min.js');
		}

		if (!config('page.usingBundle')) {
			ci('page')
				->js('/theme/orange/assets/plugins/table_sort/table_sort'.PAGE_MIN.'.js')
				->css('/theme/orange/assets/plugins/table_sort/table_sort'.PAGE_MIN.'.css');
		}
	}
}
