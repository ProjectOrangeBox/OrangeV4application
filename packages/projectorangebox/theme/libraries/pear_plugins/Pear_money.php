<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_money extends Pear_plugin
{
	public function render($number=null)
	{
		return (($number < 0) ? '-' : '').'$'.number_format(abs($number), 2);
	}
}
