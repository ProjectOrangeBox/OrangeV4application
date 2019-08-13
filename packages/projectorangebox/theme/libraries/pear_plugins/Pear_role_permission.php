<?php

class Pear_role_permission extends \Pear_plugin
{
	public function render($name=null, $value=null)
	{
		return pear::dropdown($name, ci('o_permission_model')->catalog('id', 'key'), $value, ['class'=>'form-control select3']);
	}
}
