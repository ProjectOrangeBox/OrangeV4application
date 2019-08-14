<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_checker extends Pear_plugin
{
	/* optional to add a unchecked value which will be sent when the checkbox is unchecked default's to 0 if nothing set */
	public function render($name=null, $value=null, $checked=false, $extra=[])
	{
		$unchecked = ($extra['unchecked']) ? $extra['unchecked'] : 0;

		unset($extra['unchecked']);

		if (!is_bool($checked)) {
			$checked = ($value == $checked);
		}

		return '<input type="hidden" name="'.$name.'" value="'.$unchecked.'"><input type="checkbox" name="'.$name.'" value="'.$value.'" '._stringify_attributes($extra).' '.(($checked) ? 'checked' : '').'>';
	}
}
