<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_date extends Pear_plugin
{
	public function render($timestamp=null, $format=null)
	{
		$format = (!empty($format)) ? $format : config('application.human date', 'F jS, Y, g:i a');
		$timestamp = (is_integer($timestamp)) ? $timestamp : strtotime($timestamp);

		return ($timestamp > 1000) ? date($format, $timestamp) : '';
	}
}
