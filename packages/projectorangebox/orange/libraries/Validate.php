<?php

namespace projectorangebox\orange\library;

use projectorangebox\orange\library\validate\Request;

/**
 * Orange
 *
 * An open source extensions for CodeIgniter 3.x
 *
 * This content is released under the MIT License (MIT)
 * Copyright (c) 2014 - 2019, Project Orange Box
 */

/**
 * Validate class.
 *
 * Run Validation & Filters on passed variables
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2019
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 *
 * @uses # \Errors - Orange errors
 *
 * @config ~ preset filters
 *
 */
class Validate
{
	/**
	 * Storage for attached (Closure) validations
	 *
	 * @var array
	 */
	protected $attached = [];

	/**
	 * Storage the current validations error string usually in sprintf format
	 *
	 * @var string
	 */
	protected $error_string = '';

	/**
	 * Storage for the Human readable version of the field name can be used in the error string as sprintf parameter 1 ie. Last_name Last Name
	 *
	 * @var string
	 */
	protected $error_human = '';

	/**
	 * Storage for the Error options which can be used in the error string as sprintf parameter 2 ie. [1,34,67] becomes 1, 34, 67
	 *
	 * @var string
	 */
	protected $error_params = '';

	/**
	 * Storage for the field value which can be used in the error string as sprintf parameter 3
	 *
	 * @var string
	 */
	protected $error_field_value = '';

	/**
	 * Storage for the current field data being validated
	 *
	 * @var array
	 */
	protected $field_data = [];

	/**
	 * Local reference of validate configuration
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Local reference of Orange Error Object
	 *
	 * @var \Errors
	 */
	public $errors;

	/**
	 * $request
	 *
	 * @var undefined
	 */
	public $request;

	/**
	 *
	 * Constructor
	 *
	 * @access public
	 *
	 * @param array $config []
	 *
	 */
	public function __construct(array &$config=[])
	{
		/* my config */
		$this->config = &$config;

		/* errors are stored in well... errors */
		$this->errors = ci('errors');

		/* setup the "chain" request object */
		$this->request = new Request($this,ci('input'));

		log_message('info', 'Orange Validate Class Initialized');
	}

	/**
	 *
	 * Attach a validation rule as a Closure
	 *
	 * @access public
	 *
	 * @param string $name
	 * @param closure $closure
	 *
	 * @return Validate
	 *
	 * #### Example
	 * ```php
	 * ci('validate')->attach('filter_lower',function(&$field, $options) { return strtolower($field); });
	 * ci('validate')->attach('return_true',function(&$field, $options) { return true; });
	 * ```
	 */
	public function attach(string $name, \closure $closure) : Validate
	{
		$this->attached[$this->_normalizeRuleName($name)] = $closure;

		return $this;
	}

	/**
	 * is_valid
	 *
	 * Process & Return
	 *
	 * @param mixed $input
	 * @param mixed $rules
	 * @return void
	 */
	public function is_valid($input,$rules) : bool
	{
		$this->errors->group(__METHOD__);

		$this->single($rules, $input);

		$success = $this->errors->success(__METHOD__);

		$this->errors->remove(__METHOD__);

		return $success;
	}

	/**
	 * filter
	 *
	 * Process & Return
	 *
	 * @param mixed $input
	 * @param mixed $rules
	 * @return void
	 */
	public function filter($input,$rules) /* mixed */
	{
		/* add filter_ if it's not there */
		foreach (explode('|', $rules) as $r) {
			$a[] = 'filter_'.str_replace('filter_', '', strtolower($r));
		}

		/* passed by reference */
		$this->run(implode('|', $a), $input);

		return $input;
	}

	/**
	 * run
	 *
	 * @param mixed $rules
	 * @param mixed &$fields
	 * @param mixed string
	 * @return void
	 */
	public function run($rules, &$fields, string $human = null) : Validate
	{
		return (is_array($fields)) ? $this->_multiple($rules, $fields) : $this->_single($rules, $fields, $human);
	}

	/**
	 *
	 * Run Multiple validation rules over multiple fields
	 *
	 * @access public
	 *
	 * @param array $rules []
	 * @param array &$fields []
	 *
	 * @return Validate
	 *
	 */
	protected function _multiple(array $rules = [], array &$fields) : Validate
	{
		/* save this as a reference for the validations and filters to use */
		$this->field_data = &$fields;

		/* process each field and rule as a single rule, field, and human label */
		foreach ($rules as $fieldname=>$rule) {
			$this->_single($rule['rules'], $this->field_data[$fieldname], $rule['label']);
		}

		/* break the reference */
		unset($this->field_data);

		/* now set it to empty */
		$this->field_data = [];

		return $this;
	}

	/**
	 *
	 * Run Validation rules on a single field value
	 *
	 * @access public
	 *
	 * @param $rules
	 * @param &$field
	 * @param string $human null
	 *
	 * @return Validate
	 *
	 */
	protected function _single($rules, &$field, string $human = null) : Validate
	{
		/* break apart the rules */
		if (!is_array($rules)) {
			/* is this a preset set in the configuration array? */
			$rules = (isset($this->config[$rules])) ? $this->config[$rules] : $rules;

			/* split these into individual rules */
			if (is_string($rules)) {
				$rules = explode('|', $rules);
			}
		}

		/* do we have any rules? */
		if (count($rules)) {
			/* field value before any validations / filters */
			$this->error_field_value = $field;

			/* yes - for each rule...*/
			foreach ($rules as $rule) {
				log_message('debug', 'Validate Rule '.$rule.' "'.$field.'" '.$human);

				/* no rule? exit processing of the $rules array */
				if (empty($rule)) {
					log_message('debug', 'Validate no validation rule.');

					$success = true;
					break;
				}

				/* do we have this special rule? */
				if ($rule == 'allow_empty') {
					log_message('debug', 'Validate allow_empy skipping the rest if empty.');

					if (empty($field)) {
						/* end processing of the $rules array */
						break;
					} else {
						/* skip the rest of the current foreach but don't stop processing the $rules array  */
						continue;
					}
				}

				/* setup default of no parameters */
				$param = '';

				/* do we have parameters if so split them out */
				if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
					$rule  = $match[1];
					$param = $match[2];
				}

				/* do we have a human readable field name? if not then try to make one */
				$this->error_human = ($human) ? $human : strtolower(str_replace('_', ' ', $rule));

				log_message('debug', 'Validate '.$rule.'['.$param.'] > '.$this->error_human);

				/* try to format the parameters into something human readable incase they need this in there error message  */
				if (strpos($param, ',') !== false) {
					$this->error_params = str_replace(',', ', ', $param);

					if (($pos = strrpos($this->error_params, ', ')) !== false) {
						$this->error_params = substr_replace($this->error_params, ' or ', $pos, 2);
					}
				} else {
					$this->error_params = $param;
				}

				/* hopefully error_params looks presentable now? */

				/* take action on a validation or filter - filters MUST always start with "filter_" */
				$success = (substr(strtolower($rule), 0, 7) == 'filter_') ? $this->_filter($field, $rule, $param) : $this->_validation($field, $rule, $param);

				log_message('debug', 'Validate Success '.$success);

				/* bail on first failure */
				if ($success === false) {
					/* end processing of the $rules array */
					return $this;
				}
			}
		}

		return $this;
	}

	/**
	 *
	 * Run a filter rule.
	 * Filters always start with the filter_ prefix
	 * Filters always return true (success)
	 * if you need to register a error use a validation
	 *
	 * @access protected
	 *
	 * @param &$field
	 * @param string $rule
	 * @param string $param null
	 *
	 * @return bool
	 *
	 */
	protected function _filter(&$field, string $rule, string $param = null) : bool
	{
		$class_name = $this->_normalizeRuleName($rule);

		$short_rule = substr($class_name, 7);

		if (isset($this->attached[$class_name])) {
			$this->attached[$class_name]($field, $param);
		} elseif ($namedService = \orange::findService($class_name,false)) {
			(new $namedService($this->field_data))->filter($field, $param);
		} elseif (function_exists($short_rule)) {
			$field = ($param) ? $short_rule($field, $param) : $short_rule($field);
		} else {
			throw new \Exception('Could not filter '.$rule);
		}

		/* filters don't fail */
		return true;
	}

	/**
	 *
	 * Run a validation rule.
	 * returns true on success and false on error
	 *
	 * @access protected
	 *
	 * @param &$field
	 * @param string $rule
	 * @param string $param null
	 *
	 * @return bool
	 *
	 */
	protected function _validation(&$field, string $rule, string $param = null) : bool
	{
		$class_name = $this->_normalizeRuleName($rule);
		$short_rule = substr($class_name, 9); /* chop off validate_ */

		/* default error */
		$this->error_string = '%s is not valid.';

		if (isset($this->attached[$class_name])) {
			$success = $this->attached[$class_name]($field, $param, $this->error_string, $this->field_data, $this);
		} elseif ($namedService = \orange::findService($class_name,false)) {
			$success = (new $namedService($this->field_data, $this->error_string))->validate($field, $param);
		} elseif (function_exists($short_rule)) {
			$success = ($param) ? $short_rule($field, $param) : $short_rule($field);
		} else {
			throw new \Exception('Could not validate '.$rule);
		}

		/* if success is really really false then it's a error */
		if ($success === false) {
			/**
			 * sprintf argument 1 human name for field
			 * sprintf argument 2 human version of options (computer generated)
			 * sprintf argument 3 field value
			 */
			$this->errors->add(sprintf($this->error_string, $this->error_human, $this->error_params, $this->error_field_value),$this->error_human);
		} else {
			/* not a boolean then it's something useable */
			if (!is_bool($success)) {
				$field = $success;

				$success = true;
			}
		}

		return $success;
	}

	/**
	 *
	 * normalize the rule name.
	 * if they send in a rule without a prefix then it's a validate rule
	 * because they don't need a prefix
	 * filters must always include filter_
	 *
	 * @access protected
	 *
	 * @param string $name
	 *
	 * @return string
	 *
	 */
	protected function _normalizeRuleName(string $name) : string
	{
		/* normalize to lowercase */
		$name = strtolower($name);

		/* if validate or filter is already prepended */
		$prefix = (substr($name, 0, 9) != 'validate_' && (substr($name, 0, 7) != 'filter_')) ? 	'validate_' : '';

		return $prefix.$name;
	}

} /* end class */
