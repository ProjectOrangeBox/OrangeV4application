<?php

class Pear_money extends \Pear_plugin
{
	public function render($number=null)
	{
		return (($number < 0) ? '-' : '').'$'.number_format(abs($number), 2);
	}
}
