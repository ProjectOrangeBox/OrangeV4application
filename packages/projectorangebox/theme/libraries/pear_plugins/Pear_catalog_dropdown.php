<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

// @help create a dropdown from a models catalog data.

class Pear_catalog_dropdown extends Pear_plugin
{
	public function __construct()
	{
		ci('load')->helper('form');
	}

	public function render($model=null, $name=null, $value=null, $human_column=null, $primary_key='id')
	{
		$catalog = ci('cache')->request->cache('catalog_dropdown_'.$model.$name.$human_column.$primary_key, function ($ci) use ($model,$primary_key,$human_column) {
			return ci($model)->catalog($primary_key, $human_column);
		});

		return form_dropdown($name, $catalog, $value, ['class'=>'form-control select3']);
	}
}
