<?php

namespace projectorangebox\orange\library\validate\rules;

use projectorangebox\orange\library\validate\Validation_rule;

/**
 * Validate_decimal
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
 * @help contains anything other than a decimal number.
 *
 */
class Decimal extends Validation_rule
{
	public function validate(&$field, string $options = '') : bool
	{
		$this->error_string = '%s must contain a decimal number.';
		return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $field);
	}
}
