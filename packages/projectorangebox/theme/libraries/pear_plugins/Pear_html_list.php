<?php

class Pear_html_list extends \Pear_plugin
{
	public function render($type='ul', $list=[], $attr=[], $indent='')
	{
		foreach ($list as $key => $value) {
			if (!is_array($value)) {
				$output .= $indent.chr(9).pear::html_tag('li', null, $value).PHP_EOL;
			} else {
				$output .= $indent.chr(9).pear::html_tag('li', null, pear::html_list($type, $value, null, $indent.chr(9).chr(9))).PHP_EOL;
			}
		}

		return $indent.html_tag($type, $attr, PHP_EOL.$output.$indent).PHP_EOL;
	}
}
