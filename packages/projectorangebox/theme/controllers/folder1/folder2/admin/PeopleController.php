<?php

use projectorangebox\orange\library\Controller;

class PeopleController extends Controller {

	public function index()
	{
		echo __METHOD__;

		ci('page')->asset->js('http://www.foobar.com');

		ci('page')->render('form/test');
	}

}
