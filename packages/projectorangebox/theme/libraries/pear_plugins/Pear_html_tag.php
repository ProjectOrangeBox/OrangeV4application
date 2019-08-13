<?php

class Pear_html_tag extends \Pear_plugin
{
	public function render($tag=null, $attr=[], $content=false)
	{
		if (!empty($tag)) {
			/* list of void elements (tags that can not have content) */
			$void_elements = [
				/* html4 */
				"area","base","br","col","hr","img","input","link","meta","param",
				/* html5 */
				"command","embed","keygen","source","track","wbr",
				/* html5.1 */
				"menuitem",
			];

			/* construct the HTML */
			$html = '<'.$tag;
			$html .= (!empty($attr)) ? (is_array($attr) ? _stringify_attributes($attr) : ' '.$attr) : '';

			/* a void element? */
			if (in_array(strtolower($tag), $void_elements)) {
				/* these can not have content */
				$html .= ' />';
			} else {
				/* add the content and close the tag */
				$html .= '>'.$content.'</'.$tag.'>';
			}
		} else {
			$html = $content;
		}

		return $html;
	}
}
