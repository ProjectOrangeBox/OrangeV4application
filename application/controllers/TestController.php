<?php

use projectorangebox\orange\library\Controller;

class TestController extends Controller {

	/* @httpGet ~ => *::* */
	public function index() : void
	{
		echo '<pre>';

		$input = 'Foo this & ksdjfl #*7987 8797657#$%^645 Bar';

		$output = ci('validate')->filter($input,'filter_slug');

		var_dump($output);


		$input = 'Foo this & ksdjfl #*7987 8797657#$%^645 Bar';

		$output = ci('validate')->filter($input,'filter_number');

		var_dump($output);

		$input = 'Foo this & ksdjfl #*7987 8797657#$%^645 Bar';

		$output = ci('validate')->filter($input,'filter_slug|filter_number');

		var_dump($output);

		$input = 'Foo this & ksdjfl #*7987 8797657#$%^645 Bar';

		$output = ci('validate')->isValid($input,'required|alpha');

		var_dump($output);

		$input = 'Foothis';

		$output = ci('validate')->isValid($input,'required|alpha');

		var_dump($output);
	}

	/* @httpGet ~ => *::* */
	public function test1() : void
	{
		ci('session')->set_userdata('username','Johnny');

		ci('wallet')->msg('More Info','info');
		ci('wallet')->msg('Hello World','red');
		ci('wallet')->msg('Hello World','purple');
		ci('wallet')->msg('Oh Darn!','info');

		ci('wallet')->redirect('@');
	}

	/* @httpGet ~ => *::* */
	public function test2() : void
	{
		echo '<pre>';

		ci('wallet');

		$data = ci('session')->userdata('username');

		var_dump($data);

		var_dump($this->load->get_vars());

		echo '<a href="/test/test1">Go Back</a>';
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
