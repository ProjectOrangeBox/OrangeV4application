<?php

use projectorangebox\orange\library\Controller;

class ProductController extends Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function edit($id=null)
	{
		echo '<pre>';

		var_dump($id);
	}

	public function editGet($id=null)
	{
		echo '<pre>';

		var_dump($id);
	}


}
