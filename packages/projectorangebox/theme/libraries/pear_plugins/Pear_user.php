<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_user extends Pear_plugin
{
	public function render($name=null, $arg=null)
	{
		if (method_exists(ci('user'), $name)) {
			return ($arg) ? ci('user')->$name($arg) : ci('user')->$name();
		} elseif (property_exists(ci('user'), $name)) {
			return ci('user')->$name;
		} else {
			throw new \Exception('User property or method '.$name.' not available on user entity.');
		}
	}
}
