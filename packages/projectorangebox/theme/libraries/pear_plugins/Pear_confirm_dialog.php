<?php

class Pear_confirm_dialog extends \Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/confirm-dialog/config-dialog'.PAGE_MIN.'.js');
		}
	}
}
