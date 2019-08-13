<?php
/**
 *
 * https://cdnjs.com/libraries/bootstrap-3-typeahead
 *
 * NOTE: This will add has-feedback to your form element wrapper
 *
 * ie. <div class="form-group has-feedback">
 *
 * this will position the icon inside on the right of the input field
 *
 */
class Pear_combobox extends \Pear_plugin
{
	public function __construct()
	{
		if (config('page.usingCDNs')) {
			ci('page')->js('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js');
		}
	}

	public function render($name=null, $value=null, $options=[], $extra=[])
	{
		if (is_array($options)) {
			sort($options);
			$source = json_encode($options);
		} else {
			$source = 'function(q){return $.get("/'.$options.'/"+q);}';
		}

		$html .= '<input type="text" name="'.$name.'" id="id-'.$name.'" class="typeahead form-control" value="'.esc($value).'" autocomplete="off" data-provide="typeahead">';
		$html .= '<i class="glyphicon glyphicon-triangle-bottom form-control-feedback" aria-hidden="true"></i>';
		$html .= '<script>document.addEventListener("DOMContentLoaded",function(){$("#id-'.$name.'").typeahead({';
		$html .= 'showHintOnFocus:true,';
		$html .= 'items:16,';
		$html .= 'minLength:0,';
		$html .= 'fitToElement:true,';
		$html .= 'source:'.$source;
		$html .= '}).closest("div.form-group").addClass("has-feedback");';
		$html .= '});';
		$html .= '</script>';

		return $html;
	}
}
