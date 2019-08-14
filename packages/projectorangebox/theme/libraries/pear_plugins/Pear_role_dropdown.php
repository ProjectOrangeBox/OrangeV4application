<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_role_dropdown extends Pear_plugin
{
	public function render($name=null, $value=null)
	{
		if ($value === null) {
			$prop = 'user_'.explode('_', $name)[0].'_role_id';
			$value = ci('user')->$prop;
		}

		return pear::dropdown($name, ci('o_role_model')->catalog('id', 'name'), $value, ['class'=>'form-control select3']);
	}
}
