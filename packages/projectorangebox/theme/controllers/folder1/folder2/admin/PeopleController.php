<?php

use projectorangebox\orange\library\Controller;

class PeopleController extends Controller {

	public function index()
	{
		ci('config')->dotItem('foo.bar');

		echo findView('cats');

		$event = ci('event');

		echo __METHOD__;
	}

}
