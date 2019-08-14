<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_e extends Pear_plugin
{
	public function render($string=null, $functions=null)
	{
		$html = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

		if ($functions) {
			$functions = explode('|', $functions);

			foreach ($functions as $function) {
				if (function_exists($function)) {
					$html = $function($html);
				}
			}
		}

		return $html;
	}
}
