<?php

namespace projectorangebox\theme\middleware;

use projectorangebox\orange\library\abstracts\Middleware;

class PublicMiddleware extends middleware
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
		/* this will speed it up a little bit */
		ci('output')->parse_exec_vars = false;

		ci('page')->set_default_view(str_replace('controller/','/',ci('router')->fetch_route()));

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
		return true;
	}

} /* end class */