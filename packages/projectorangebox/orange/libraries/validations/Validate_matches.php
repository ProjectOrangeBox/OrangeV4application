<?php

namespace projectorangebox\orange\library\validations;

use projectorangebox\orange\library\abstracts\Validate;

/**
 * Validate_matches
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
 * @help matches the field in the parameter.
 *
 */
class Validate_matches extends Validate
{
	public function validate(&$field, string $options = '') : bool
	{
		$this->error_string = '%s does not match %s.';

		return isset($this->field_data[$options]) ? ($field === $this->field_data[$options]) : false;
	}
}
