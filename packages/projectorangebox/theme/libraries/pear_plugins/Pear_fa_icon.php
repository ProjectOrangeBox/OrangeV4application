<?php

class Pear_fa_icon extends \Pear_plugin
{
	public function render($name='')
	{
		return '<i class="fa fa-'.$name.'"></i>';
	}
}
