<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_tab_title extends Pear_plugin
{
	public function render($string=null)
	{
		return htmlspecialchars(ucwords($string), ENT_QUOTES, 'UTF-8');
	}
}
