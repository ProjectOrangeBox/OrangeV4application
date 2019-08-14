<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

namespace projectorangebox\orange\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

/**
 * Orange
 *
 * An open source extensions for CodeIgniter 3.x
 *
 * This content is released under the MIT License (MIT)
 * Copyright (c) 2014 - 2019, Project Orange Box
 */

/**
 * Validation Filter
 *
 * @help end a section started with pear::section()
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2019
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 *
 */

class Pear_wrap extends Pear_plugin
{
	public function render(string $tag,array $attr = [],string $content = '',bool $escape=true)
	{
		$selfClosing = ['area','base','br','embed','hr','iframe','img','input','link','meta','param','source'];

		$html = '<'.$tag.' '.str_replace("=", '="', http_build_query($attr, null, '" ', PHP_QUERY_RFC3986)).'">';

		if (!empty($content)) {
			$html .= ($escape) ? htmlentities($content) : $content;
		}

		if (!in_array($tag,$selfClosing)) {
			$html .= '</'.$tag.'>';
		}

		return $html;
	}
} /* end plugin */
