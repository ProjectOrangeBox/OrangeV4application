<?php

use packages\projectorangebox\orange\libraries\Controller;

class WelcomeController extends \CI_Controller {

	public function index() : void
	{
		$this->load->view('welcome_message');
	}

	public function fourohfour() : void
	{
		show_404();
	}

	public function test() : void
	{
		ci('libraries\Tester')->set('Hello World');

		echo ci('libraries\Tester')->get();

		ci('libraries\Tester','cowboy');

		ci('cowboy')->set('Hello Cow Boy World');

		echo ci('cowboy')->get();
	}

	public function remap() : void
	{
		echo '<pre>';

		$input = [
			'id' => 89,
			'name' => 'Johnny Appleseed',
			'number' => 21,
			'remove' => 'foobar',
			'foo' => 'Yes Please',
			'model2|name' => 'Jenny Appleseed',
			'model2|age' => 20,
			'model3|name' => 'my name',
			'model3|age' => 23,
			'repeatable' => [
				0 => [
					'id' => 45,
					'firstname' => 'Johnny',
					'lastname' => 'Appleseed',
					'checkers' => 0,
				],
				1 => [
					'id' => 78,
					'firstname' => 'Don',
					'lastname' => 'Jones',
					'checkers' => 1,
				],
				2 => [
					'id' => 83,
					'firstname' => 'Frank',
					'lastname' => 'Peters',
					'checkers' => 1,
				],
			],
		];

	 /*
	 * it is possible to append [] (brackets) after the field name in the
	 * copy or move array in order to copy or move the value to the output of a array if needed
	 * $copy = ['roles|parent_id[]'=>'id'];
	 * This would copy the id to each role index
	 */

		$output = ci('input')->request_remap(['bar'=>'foo','parent.repeatable[]parent_id'=>'id','model2.parent_id'=>'id'],['age'=>'number'],['remove','foo'],'parent','','|','',$input);


		var_dump($output);
	}

} /* end class */
