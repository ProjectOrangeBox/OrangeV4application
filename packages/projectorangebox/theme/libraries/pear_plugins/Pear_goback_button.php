<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_goback_button extends Pear_plugin
{
	public function render($uri='', $title='Go Back', $attributes=[])
	{
		$default_attributes = ['class'=>'btn btn-default btn-sm js-esc'];
		$attributes = array_merge($default_attributes, (array)$attributes);

		return anchor($uri, '<i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> '.$title, $attributes);
	}
}
