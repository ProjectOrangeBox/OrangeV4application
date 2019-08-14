<?php

use projectorangebox\orange\library\Controller;

class TestController extends Controller {

	/* @httpGet ~ => *::* */
	public function index() : void
	{
		echo '<pre>';

		$cache = ci('cache');

		$cache->save('id','data');

		$cache->request->save('id','data');
		$cache->export->save('foobar',true);
		$cache->file->save('key','another test');

		$caches = ['apc','dummy','file','memcached','redis','wincache','export','request'];

		foreach ($caches as $name) {
			var_dump($name,$cache->is_supported($name));
		}
	}

	/* @httpGet ~ => *::* */
	public function test1() : void
	{
		ci('session')->set_userdata('username','Johnny');


		ci('config')->set_item('name.is','Johnny Appleseed');

		$x = ci('config')->item('name.is');

		var_dump($x);
	}

	/* @httpGet ~ => *::* */
	public function test2() : void
	{
		$data = ci('session')->userdata('username');



		var_dump($data);

	}

	/* @httpGet ~ => *::* */
	public function test3() : void
	{
		$this->load->view('form');
	}

	/* @httpGet ~ => *::* */
	public function test4() : void
	{
		$this->load->view('form');
	}


} /* end class */
