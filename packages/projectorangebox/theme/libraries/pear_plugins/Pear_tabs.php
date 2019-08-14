<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_tabs extends Pear_plugin
{
	public function render($array=[])
	{
		return array_keys($array);
	}
}
