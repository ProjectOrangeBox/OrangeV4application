<?php

namespace projectorangebox\orange\library\validate;

use projectorangebox\orange\library\Validate;
use projectorangebox\orange\library\Input;

class request {
	/**
	 * $input
	 *
	 * @var undefined
	 */
	protected $input;

	/**
	 * $validate
	 *
	 * @var undefined
	 */
	protected $validate;

	/**
	 * __construct
	 *
	 * @param mixed $parent
	 * @return void
	 */
	public function __construct(Validate $parent,Input $input)
	{
		$this->validate = $parent;
		$this->input = $input;
	}

	/**
	 * is_valid
	 *
	 * @param array $keysRules
	 * @return void
	 */
	public function is_valid(array $keysRules) : bool
	{
		$group = 'request_is_valid';

		$this->validate->errors->group($group);

		foreach ($keysRules as $key=>$rules) {
			$field = $this->input->request($key);

			$this->validate->run($rules, $field);
		}

		return $this->validate->errors->success($group);
	}

	/**
	 * filter
	 *
	 * @param array $keysRules
	 * @return void
	 */
	public function filter(array $keysRules) : void
	{
		foreach ($keysRules as $key=>$rules) {
			$field = $this->input->request($key);

			$ra = [];

			/* add filter_ if it's not there */
			foreach (explode('|', $rules) as $r) {
				$ra[] = 'filter_'.str_replace('filter_', '', strtolower($r));
			}

			/* passed by reference */
			$this->validate->run(implode('|', $ra), $field);

			$this->input->set_request($key,$field);
		}
	}

} /* end class */