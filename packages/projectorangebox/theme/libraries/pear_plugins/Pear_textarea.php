<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_textarea extends Pear_plugin
{
	public function render($name=null, $value=null, $options=null)
	{
		if (is_array($name)) {
			$value = $options['value'];
			$options = $name;
			$name = $options['name'];

			unset($options['value']);
		}

		$defaults = [
			'name'=>$name,
			'cols'=>'40',
			'rows'=>'10',
			'class'=>'',
			'id'=>$name,
		];

		return '<textarea '._stringify_attributes(array_merge($defaults, $options)).'>'.$value."</textarea>\n";
	}
}
