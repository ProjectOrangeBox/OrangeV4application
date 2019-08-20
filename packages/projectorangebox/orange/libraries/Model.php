<?php

namespace projectorangebox\orange\library;

/**
 * Orange
 *
 * An open source extensions for CodeIgniter 3.x
 *
 * This content is released under the MIT License (MIT)
 * Copyright (c) 2014 - 2019, Project Orange Box
 */

/**
 * Extension to CodeIgniter Model Class
 * Provides validation in the model
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

class Model extends \CI_Model
{
	/**
	 * Formatted array of rules for this model
	 * ['id' => ['field' => 'id', 'label' => 'Id', 'rules' => 'required|integer|max_length[10]|less_than[4294967295]|filter_int[10]']]
	 *
	 * @var Array
	 */
	protected $rules = [];

	/**
	 * Used to tell the model to skip all rule validations
	 *
	 * @var Boolean
	 */
	protected $skip_rules = false;

	/**
	 * Named rules sets to use on Model method called
	 *
	 * [
	 *		'basic_form'=>'id,first_name,last_name',
	 *		'adv_form'=>'id,first_name,last_name,age,weight',
	 *		'insert'=>'first_name,last_name,age,weight',
	 *		'update'=>'id,first_name,last_name,age,weight',
	 * ]
	 *
	 * @var Array
	 */
	protected $rule_sets = [];

	/**
	 * Name of the object
	 *
	 * @var String
	 */
	protected $object = null;

	public function __construct()
	{
		parent::__construct();

		log_message('info', 'Orange Model Class Initialized');
	}

	/**
	 * Get the object name
	 *
	 * @access public
	 *
	 * @return String
	 *
	 */
	public function object() : String
	{
		return $this->object;
	}

	/**
	 * Get the models Rules
	 *
	 * @access public
	 *
	 * @return Array
	 *
	 */
	public function rules() : array
	{
		return $this->rules;
	}

	/**
	 * Get a rule by column name or column name and section
	 *
	 * @param $key column name
	 * @param $section column section
	 *
	 * @return mixed
	 */
	public function rule(string $key, $section = null)
	{
		log_message('info', 'orange model::rule '.$key.' '.$section);

		$rule = ($section) ? $this->rules[$key][$section] : $this->rules[$key];

		return ($rule === null) ? false : $rule;
	}

	/**
	 *
	 * Clear any validation errors for this object
	 *
	 * @access public
	 *
	 * @return model
	 *
	 */
	public function clear() : Model
	{
		log_message('info', 'orange model::clear '.$this->object);

		/* validation wrapper */
		ci('validate')->clear($this->object);

		return $this;
	}

	/**
	 *
	 * Preform Model Validation
	 * If rules is boolean true
	 *   then we auto use the rule names which match the data array keys
	 * If rules is an array
	 *   then we use them verbatim
	 * If rules is a string
	 *   then we convert it to a array by separating the string on commas
	 *
	 * @access public
	 *
	 * @param Array &$data key value pairs to test
	 * @param Mixed $rules rules to use for the validations
	 *
	 * @return Bool Success
	 *
	 */
	public function validate(array &$data, $rules = true) : Bool
	{
		log_message('info', 'orange model::validate');

		/**
		 * if it's already a array then it's already in the format we need
		 */
		if (!is_array($rules)) {
			/**
			 * if rules is true then just use the data array keys as the fields to validate to
			 */
			if ($rules === true) {
				$rules_names = array_keys($data);
			} elseif (is_string($rules)) {
				/**
				 * if it's a string then see if it's a rule set if not treat as a comma sep list of field to validate
				 */
				$rules_names = explode(',', (isset($this->rule_sets[$rules]) ? $this->rule_sets[$rules] : $rules));
			}

			/**
			 * copy all the rules so we can modify the copy
			 */
			$rules = $this->rules;

			/**
			 * now filter out the rules we don't need
			 */
			$this->only_columns($rules, $rules_names);
		}

		/**
		 * let's make sure the data "keys" have rules
		 */
		$this->only_columns($data, $rules);

		/**
		 * Save the current group in validate
		 * so we can put it back after this model is done validating this model
		 */
		$previousErrorGroup = ci('validate')->get_group();

		/**
		 * did we actually get any rules?
		 */
		if (count($rules)) {
			/**
			 * run the rules on the data array
			 */
			ci('validate')->group($this->object)->multiple($rules, $data);
		}

		/**
		 * return if we got any errors
		 */
		$success = ci('validate')->success($this->object);

		/**
		 * we are done put back the previous error group
		 */
		ci('validate')->group($previousErrorGroup);

		return $success;
	}

	/**
	 *
	 * remove matching keys in the data array from input in columns
	 * remove the matching keys in the data array from input in columns
	 * columns can be a array ['firstname','lastname','age'] or comma sep string 'firstname,lastname,age'
	 *
	 * @access public
	 *
	 * @param Array &$data
	 * @param $columns []
	 *
	 * @return model
	 *
	 */
	public function remove_columns(array &$data, $columns = []) : Model
	{
		log_message('info', 'orange model::remove_columns');

		/**
		 * convert string with commas to array
		 */
		$columns = (!is_array($columns)) ? explode(',', $columns) : $columns;

		/**
		 * remove any data "key" in columns array
		 */
		$data = array_diff_key($data, array_combine($columns, $columns));

		return $this;
	}

	/**
	 *
	 * only the matching keys in the data array from input in columns
	 * columns can be a array ['firstname','lastname','age'] or comma sep string 'firstname,lastname,age'
	 *
	 * @access public
	 *
	 * @param Array &$data
	 * @param $columns []
	 *
	 * @return model
	 *
	 */
	public function only_columns(array &$data, $columns = []) : Model
	{
		log_message('info', 'orange model::only_columns');

		/**
		 * convert string with commas to array
		 */
		$columns = (!is_array($columns)) ? explode(',', $columns) : $columns;

		/**
		 * let' make sure the values are singular not an array if they are singular then create the key/value pair
		 */
		if (!is_array(current($columns))) {
			$columns = array_combine($columns, $columns);
		}

		/**
		 * remove any data "key" not in columns array
		 */
		$data = array_intersect_key($data, $columns);

		return $this;
	}

} /* end class */
