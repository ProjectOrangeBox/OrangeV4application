<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_fa_enum_icon extends Pear_plugin
{
	public function render($value=-1, $string = 'circle-o|check-circle-o', $extra='fa-lg', $delimiter = '|')
	{
		$enum = explode($delimiter, $string);

		return '<i class="fa fa-'.$enum[(int)$value].' '.$extra.'"></i>';
	}
}
