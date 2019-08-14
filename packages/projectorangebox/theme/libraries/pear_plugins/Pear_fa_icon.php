<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_fa_icon extends Pear_plugin
{
	public function render($name='')
	{
		return '<i class="fa fa-'.$name.'"></i>';
	}
}
