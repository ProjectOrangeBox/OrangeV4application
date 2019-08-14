<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_form_helpers extends Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/form_helpers/form_helpers'.PAGE_MIN.'.js');
		}
	}
}
