<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_field_range extends Pear_plugin
{
	public function __construct()
	{
		ci('page')->style('.pear_field_range{margin-top:10px}');
	}

	public function render($name=null, $value=null, $extra=[])
	{
		$default_class = 'pear_field_range';

		$extra = array_merge(['name'=>$name,'value'=>$value], $extra);
		$extra['class'] = (isset($extra['class'])) ? $extra['class'].' '.$default_class : $default_class;
		$extra['id'] = (isset($extra['id'])) ? $extra['id'] : $extra['name'].'InputId';

		/*
		Supported Common Attributes
		autocomplete, list, max, min, and step
		*/

		return '<input type="range" oninput="'.$extra['name'].'OutputId.value = '.$extra['id'].'.value" '._stringify_attributes($extra).'>
		</div><div class="col-md-1"><output class="" name="'.$extra['name'].'" id="'.$extra['name'].'OutputId">'.$extra['value'].'</output>';
	}
}
