<?php

namespace packages\projectorangebox\theme\middleware;

use projectorangebox\orange\library\abstracts\Middleware;

class PrivateMiddleware extends Middleware
{
	/**
	 *
	 * Called on request before the controller method is called
	 * This method is overridden in the child class
	 *
	 * @access public
	 *
	 * @param array $request
	 * @return boolean
	 *
	 */
	public function request(\CI_Input &$input) : bool
	{
		echo '<h1>Hello "'.$input->get('name').'"</h1>';

		return true;
	}

	/**
	 *
	 * Called on responds after the controller method is called
	 * This method is overridden in the child class
	 *
	 * @access public
	 *
	 * @param string $output
	 * @return boolean
	 *
	 */
	public function response(string &$output) : bool
	{
		$output .= '<h1>Hello World</h1>';

		return true;
	}

} /* end class */