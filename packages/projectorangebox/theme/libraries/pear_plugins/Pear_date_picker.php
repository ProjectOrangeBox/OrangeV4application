<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_date_picker extends Pear_plugin
{
	public function render($name='', $value=null, $extra=[])
	{
		$extra['format'] = ($extra['format']) ? $extra['format'] : 'MM/DD/YYYY';
		$extra['icon'] = ($extra['icon']) ? $extra['icon'] : 'calendar';

		return pear::date_picker_main($name, $value, $extra);
	}
}
