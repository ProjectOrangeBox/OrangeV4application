<?php

class Pear_notify extends \Pear_plugin
{
	public function __construct() {
		if (!config('page.usingBundle')) {
			ci('page')
				->css('/theme/orange/assets/plugins/notify/notify'.PAGE_MIN.'.css')
				->js('/theme/orange/assets/plugins/notify/notify'.PAGE_MIN.'.js');
		}
	}
}
