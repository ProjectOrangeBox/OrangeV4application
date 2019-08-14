<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_new_button extends Pear_plugin
{
	public function render($uri='', $title='New', $attributes=[])
	{
		$default_attributes = ['class'=>'btn btn-default btn-sm js-new'];
		$attributes = array_merge($default_attributes, (array)$attributes);

		return anchor($uri, '<i class="fa fa-magic" aria-hidden="true"></i> '.$title, $attributes);
	}
}
