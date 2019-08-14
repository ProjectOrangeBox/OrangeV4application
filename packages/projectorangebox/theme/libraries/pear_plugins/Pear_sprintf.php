<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_sprintf extends Pear_plugin
{
	public function render($value=null, $format='', $empty_is='')
	{
		$html = ($value == $empty_is || empty($value)) ? '': sprintf($format, $value);

		return htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
	}
}
