<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_title extends Pear_plugin
{
	public function render($title='', $icon=null, $help='')
	{
		return '<h3>'.(($icon) ? '<i class="fa fa-'.$icon.'"></i> '.$title : $title).' <small>'.$help.'</small></h3>';
	}
}
