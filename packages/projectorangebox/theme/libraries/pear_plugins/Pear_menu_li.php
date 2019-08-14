<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_menu_li extends Pear_plugin
{
	public function render($permission=null, $url=null, $text=null)
	{
		if (ci('user')->can($permission)) {
			echo '<li><a href="'.site_url($url).'">'.$text.'</a></li>';
		}
	}
}
