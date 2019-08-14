<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

class Pear_delete_button extends Pear_plugin
{
	public function render($uri='', $attributes=[])
	{
		$name = (isset($attributes['primary_key'])) ? $attributes['primary_key'] : 'id';

		$html  = '<form action="'.$uri.'" method="delete" data-confirm="true" data-fadeout="tr">';
		$html .= '<input type="hidden" name="'.$name.'" value="'.bin2hex($attributes[$name]).'">';
		$html .= '<a href="#" class="js-button-submit">';
		$html .= '<i class="fa fa-trash fa-lg" aria-hidden="true">';
		$html .= '</i>';
		$html .= '</a>';
		$html .= '</form>';

		return $html;
	}
}
