<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_select3 extends Pear_plugin
{
	public function __construct()
	{
		if (config('page.usingCDNs')) {
			ci('page')
				->js('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js')
				->css('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css');
		}

		ci('page')->domready("$('.select3').selectpicker();");
	}

	public function render($name=null, $options=null, $value=null, $extras=[])
	{
		if (count($options) > 20) {
			$extras['data-live-search'] = 'true';
		}

		$extras['class'] .= ' select3';

		return pear::dropdown($name, $options, $value, $extras);
	}
}
