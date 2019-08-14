<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_tab_id extends Pear_plugin
{
	public function render($value=null)
	{
		return md5($value);
	}
}
