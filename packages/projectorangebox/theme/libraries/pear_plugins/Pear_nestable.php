<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_nestable extends Pear_plugin
{
	public function __construct()
	{
		ci('page')
			->js_variable('nestable_handler', ci('page')->data('nestable_handler'))
			->domready('plugins.nestable.init();');

		if (config('page.usingCDNs')) {
			ci('page')->js('//cdnjs.cloudflare.com/ajax/libs/Nestable/2012-10-15/jquery.nestable.min.js');
		}

		if (!config('page.usingBundle')) {
			ci('page')
				->js('/theme/orange/assets/plugins/nestable/nestable'.PAGE_MIN.'.js')
				->css('/theme/orange/assets/plugins/nestable/nestable'.PAGE_MIN.'.css');
		}
	}
}
