<?php

class Pear_tab_id extends \Pear_plugin
{
	public function render($value=null)
	{
		return md5($value);
	}
}
