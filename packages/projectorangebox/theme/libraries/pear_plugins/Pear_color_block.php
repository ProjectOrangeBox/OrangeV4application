<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_color_block extends Pear_plugin
{
	public function render($color_hex=null)
	{
		return '<div style="margin-top: -4px;font-size: 120%;color:#'.trim($color_hex, '#').'"><i class="fa fa-square"></i></div>';
	}
}
