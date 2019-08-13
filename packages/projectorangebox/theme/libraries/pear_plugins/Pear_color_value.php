<?php

class Pear_color_value extends \Pear_plugin
{
	public function render($color=null, $with_hash=true)
	{
		return(($with_hash) ? '#' : '').trim($color, '#');
	}
}
