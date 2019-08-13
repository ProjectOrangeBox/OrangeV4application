<?php

class Pear_tab_title extends \Pear_plugin
{
	public function render($string=null)
	{
		return htmlspecialchars(ucwords($string), ENT_QUOTES, 'UTF-8');
	}
}
