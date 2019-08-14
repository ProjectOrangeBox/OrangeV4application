<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_example_open extends Pear_plugin
{
	public function render()
	{
		ob_start();
	}
}
