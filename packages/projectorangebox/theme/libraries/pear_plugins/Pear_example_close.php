<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_example_close extends Pear_plugin
{
	public function render()
	{
		$buffer = ob_get_contents();

		ob_end_clean();

		echo strtolower($buffer);
	}
}
