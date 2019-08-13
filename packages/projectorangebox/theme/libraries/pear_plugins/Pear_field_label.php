<?php

class Pear_field_label extends \Pear_plugin
{
	public function render($model=null, $field=null, $override_text=null)
	{
		$rule = (class_exists($model, false)) ? ci($model)->rule($field) : [];

		$required = ((strpos('|'.$rule['rules'].'|', '|required|') !== false) ? ' required' : '');

		if ($override_text) {
			$text = $override_text;
		} else {
			$text = ((empty($rule['label'])) ? ucwords(strtolower($field)) : $rule['label']);
		}

		return '<label class="col-md-3 control-label'.$required.'">'.$text.'</label>';
	}
}
