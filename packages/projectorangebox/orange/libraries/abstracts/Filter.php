<?php

namespace projectorangebox\orange\library\abstracts;

use projectorangebox\orange\library\abstracts\Validate;

/**
 * Orange
 *
 * An open source extensions for CodeIgniter 3.x
 *
 * This content is released under the MIT License (MIT)
 * Copyright (c) 2014 - 2019, Project Orange Box
 */

/**
 * Filter Base Class
 *
 * All other filters should extend this class
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2019
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 * @filesource
 *
 */
abstract class Filter extends Validate
{
	/**
	 *
	 * Abstract for filter method
	 *
	 * @access public
	 *
	 * @param &$field data to be filtered
	 * @param string $options
	 *
	 * @return void always returns true in the calling class
	 *
	 */
	public function filter(&$field, string $options = '') : void
	{
	}
} /* end class */
