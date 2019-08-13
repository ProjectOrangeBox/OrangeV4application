<?php

namespace projectorangebox\orange\library\validations;

use projectorangebox\orange\library\abstracts\Validate;

/**
 * Validate_less_than
 * Insert description here
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2018
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 *
 * required
 * core:
 * libraries:
 * models:
 * helpers:
 * functions:
 *
 * @help is less than the parameter value or not numeric.
 *
 */
class Validate_less_than extends Validate
{
	public function validate(&$field, string $options = '') : bool
	{
		$this->error_string = '%s must contain a number less than %s.';

		if (!is_numeric($field)) {
			return false;
		}

		return is_numeric($field) ? ($field < $options) : false;
	}
}
