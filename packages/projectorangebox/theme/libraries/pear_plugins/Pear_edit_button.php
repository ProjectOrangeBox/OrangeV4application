<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_edit_button extends Pear_plugin
{
	public function render($uri='', $attributes=[])
	{
		return anchor($uri, '<i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>', $attributes);
	}
}
