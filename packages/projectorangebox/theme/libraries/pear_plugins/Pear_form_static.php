<?php

class Pear_form_static extends \Pear_plugin
{
	public function render($string='')
	{
		return '<p class="form-control-static">'.htmlspecialchars($string, ENT_QUOTES, 'UTF-8').'</p>';
	}
}
