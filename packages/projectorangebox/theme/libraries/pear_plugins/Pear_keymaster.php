<?php

class Pear_keymaster extends \Pear_plugin
{
	public function __construct()
	{
		if (config('page.usingCDNs')) {
			ci('page')->js('//cdnjs.cloudflare.com/ajax/libs/keymaster/1.6.1/keymaster.min.js');
		}

		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/keymaster/onready_keymaster'.PAGE_MIN.'.js');
		}
	}
}
