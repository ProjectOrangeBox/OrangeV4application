<?php

use packages\projectorangebox\orange\libraries\Controller;

class WelcomeController extends Controller {

	public function index()
	{
		ci('config')->dotItem('foo.bar');

		echo findView('cats');

		$auth = ci('event');

		echo __METHOD__;
	}

}
