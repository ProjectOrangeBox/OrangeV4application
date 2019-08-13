<?php

class Pear_edit_button extends \Pear_plugin
{
	public function render($uri='', $attributes=[])
	{
		return anchor($uri, '<i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>', $attributes);
	}
}
