<?php

class Pear_bootstrapNav extends \Pear_plugin
{
	public function render($parentId=-1, $config=[], $filter=true)
	{
		return ci('nav_library')->build_bootstrap_nav($parentId,$config,$filter);
	}
}
