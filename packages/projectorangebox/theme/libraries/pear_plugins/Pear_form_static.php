<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_form_static extends Pear_plugin
{
	public function render($string='')
	{
		return '<p class="form-control-static">'.htmlspecialchars($string, ENT_QUOTES, 'UTF-8').'</p>';
	}
}
