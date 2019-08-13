<?php

class Pear_color_fa_icon extends \Pear_plugin
{
	public function render($color=null, $icon=null)
	{
		return '<span style="background:#'.$color.';padding:2px 7px;border-radius:2px"><i style="color:white" class="fa fa-'.$icon.'"></i></span>';
	}
}
