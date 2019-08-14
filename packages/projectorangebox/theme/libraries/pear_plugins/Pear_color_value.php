<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_color_value extends Pear_plugin
{
	public function render($color=null, $with_hash=true)
	{
		return(($with_hash) ? '#' : '').trim($color, '#');
	}
}
