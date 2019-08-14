<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_header_button extends Pear_plugin
{
	public function render($uri='', $title='', $attributes=[])
	{
		$default_attributes = ['class'=>'btn btn-default btn-sm'];
		$attributes = array_merge($default_attributes, (array)$attributes);

		return anchor($uri, '<i class="fa fa-'.$attributes['icon'].'" aria-hidden="true"></i> '.$title, $attributes);
	}
}
