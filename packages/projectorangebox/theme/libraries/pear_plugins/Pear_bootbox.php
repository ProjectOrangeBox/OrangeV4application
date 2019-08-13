<?php

class Pear_bootbox extends \Pear_plugin
{
	public function __construct()
	{
		if (config('page.usingCDNs')) {
			ci('page')->js('//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js',PAGE::PRIORITY_HIGH);
		}
	}
}
