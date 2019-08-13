<?php

use projectorangebox\orange\library\Controller;

class FormController extends Controller {

	/* @httpGet ~ => *::* */
	public function index() : void
	{
		$this->load->view('form');
	}

	/* @httpGet ~ => *::* */
	public function test_filter() : void
	{
		$input = 'This # is a @test';

		$output = filter('filename',$input);

		var_dump($output);
	}

	/* @httpGet ~ => *::* */
	public function test_pear_plugin() : void
	{
		ci('page')->render();


	}

	/* @httpGet ~ => *::* */
	public function test_validation() : void
	{
		echo '<pre>';

		$input = 'This # is a @test';

		$output = valid('alpha',$input);

		var_dump($output);

		$input = 'Thisisatest';

		$output = valid('alpha_space',$input);

		var_dump($output);

		$input = 'This # is a @test';

		ci('validate')->single('alpha', $input);

		var_dump(ci('errors'));
	}

	public function post() : void
	{
		echo '<pre>';

		$remap = ['model.repeatable.#.parent_id'=>'model.id','model.copied'=>'model.moveme','model.removeme','model.moveme'];

		$output = ci('input')->requestRemap($remap); /* optional boolean replace input request */

		var_dump($output);

		$remap = ['names'=>'model','model','names.copied'];

		$output = (new \packages\projectorangebox\orange\libraries\input\RequestRemap)->processArray($remap,$output);

		var_dump($output->get());
	}

	public function dotnotation() : void
	{
		echo '<pre>';

		$dot = new \packages\projectorangebox\orange\libraries\Dot;

		$dot->set('bar.baz.foo', 'this is a test');
		$dot->set('bar.cat', 'cat here');
		$dot->add('bar.cat.#.name', 'cat here');
		$dot->add('bar.cat.#.age', 'cat here');

		foreach ($dot as $key=>$value) {
			var_dump($key,$value);
		}

	}

} /* end class */
