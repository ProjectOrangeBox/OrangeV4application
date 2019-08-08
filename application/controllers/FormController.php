<?php

class FormController extends CI_Controller {

	public function index() : void
	{
		$this->load->view('form');
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
