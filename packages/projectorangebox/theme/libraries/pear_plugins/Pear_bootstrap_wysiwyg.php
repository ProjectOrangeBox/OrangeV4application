<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

/**
 *
 * https://developer.mozilla.org/en-US/docs/Rich-Text_Editing_in_Mozilla#Executing_Commands
 * http://github.com/mindmup/bootstrap-wysiwyg
 *
 * Used in conjunction with pear::bootstrap_wysiwyg($name,$value,$extra)
 *
 * extra['height'] in pixels
 * extra['toolbar'] must be available in PHP searchable paths ../libraries/plugins/bootstrap_wysiwyg_toolbars/*
 *
 * @help WYSIWYG editor
 *
 */
class Pear_bootstrap_wysiwyg extends Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')
			->js([
				'/theme/orange/assets/plugins/bootstrap_wysiwyg/vendor/jquery.hotkeys'.PAGE_MIN.'.js',
				'/theme/orange/assets/plugins/bootstrap_wysiwyg/vendor/bootstrap-wysiwyg'.PAGE_MIN.'.js',
				'/theme/orange/assets/plugins/bootstrap_wysiwyg/plugin_bootstrap_wysiwyg'.PAGE_MIN.'.js',
			])
			->css('/theme/orange/assets/plugins/bootstrap_wysiwyg/plugin_bootstrap_wysiwyg'.PAGE_MIN.'.css');
		}
	}

	public function render($name=null, $value=null, $extra=[])
	{
		$extra = array_merge(['height'=>320,'toolbar'=>'default_toolbar'], $extra);

		$toolbar = ($toolbar_file = stream_resolve_include_path('libraries/plugins/bootstrap_wysiwyg_toolbars/'.$extra['toolbar'].'.php')) ? file_get_contents($toolbar_file) : '';

		$html  = '<div class="btn-toolbar wysiwyg-toolbar" data-role="editor-'.$name.'-toolbar" data-target="#wysiwyg-'.$name.'">';
		$html .= $toolbar;
		$html .= '</div>';
		$html .= '<div style="height: '.$extra['height'].'px" class="bootstrap-wysiwyg" id="wysiwyg-'.$name.'" data-textarea="'.$name.'" contenteditable="true">'.$value.'</div>';

		return $html;
	}
}
