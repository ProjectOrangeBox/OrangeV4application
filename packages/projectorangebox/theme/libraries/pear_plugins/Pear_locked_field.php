<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_locked_field extends Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/plugin-locked-field/plugin_locked_field'.PAGE_MIN.'.js');
		}
	}

	public function render($name=null, $value=null, $extra=[])
	{
		$extra = array_merge(['default'=>'lock','can'=>'####','class'=>''], $extra);

		$show_lock = true;

		/* if this is a post (ie. new record) don't show the lock / unlock */
		if (strtolower($extra['method']) == 'post') {
			$extra['default'] = '';
			$show_lock = false;
		}

		$html = '<input type="text" '.(($extra['default'] == 'lock') ? 'readonly' : '').' id="'.$name.'" name="'.$name.'" value="'.$value.'" class="'.$extra['class'].'" style="width:100%; display:inline">';

		if ($show_lock) {
			if (ci('user')->can($extra['can'])) {
				$html .= '<a style="margin-left: -24px" class="js-locked-field-lock" href="#" data-lock="true"><i class="fa fa-'.(($show_lock) ? 'lock' : 'unlock').'"></i></a>';
			}
		}

		return $html;
	}
}
