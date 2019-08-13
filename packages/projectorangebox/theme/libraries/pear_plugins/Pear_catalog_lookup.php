<?php

class Pear_catalog_lookup extends \Pear_plugin
{
	public function render($model=null, $value=null, $human_column=null, $primary_key='id')
	{
		$catalog = ci($model)->catalog($primary_key,$human_column,null,null,true,true,true);

		return $catalog[$value];
	}
}
