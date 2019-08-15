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

		$cache->apc->save('test','foobar');

		$caches = ['apc','dummy','file','memcached','redis','wincache','export','request'];

		foreach ($caches as $name) {
			var_dump($name,$cache->is_supported($name));
		}

		echo PHP_EOL.PHP_EOL.'>> Dump by Tags'.PHP_EOL.PHP_EOL;

		/* apc */
		$cache->apc->save('keys',['index'=>'values']);

		var_dump($cache->apc->get('keys'));

		$cache->apc->deleteByTags('keys');

		var_dump($cache->apc->get('keys'));

		echo str_repeat('-',23).PHP_EOL;

		/* file */
		$cache->file->save('keys',['index'=>'values']);

		var_dump($cache->file->get('keys'));

		$cache->file->deleteByTags('keys');

		var_dump($cache->file->get('keys'));

		echo str_repeat('-',23).PHP_EOL;

		/* dummy */
		$cache->dummy->save('keys',['index'=>'values']);

		var_dump($cache->dummy->get('keys'));

		$cache->dummy->deleteByTags('keys');

		var_dump($cache->dummy->get('keys'));

		echo str_repeat('-',23).PHP_EOL;

		/* export */
		$cache->export->save('keys',['index'=>'values']);

		var_dump($cache->export->get('keys'));

		$cache->export->deleteByTags('keys');

		var_dump($cache->export->get('keys'));

		echo str_repeat('-',23).PHP_EOL;

		/* request */
		$cache->request->save('keys',['index'=>'values']);

		var_dump($cache->request->get('keys'));

		$cache->request->deleteByTags('keys');

		var_dump($cache->request->get('keys'));

		echo str_repeat('-',23).PHP_EOL;

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
