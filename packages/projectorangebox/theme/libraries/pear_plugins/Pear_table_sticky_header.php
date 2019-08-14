<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_table_sticky_header extends Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/table_sticky_header/jquery.stickytableheaders'.PAGE_MIN.'.js');
		}

		ci('page')->domready("$('.table-sticky-header').stickyTableHeaders({fixedOffset: $('.page-header.navbar.navbar-fixed-top')});");
	}
}
