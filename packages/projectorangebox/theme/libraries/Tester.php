<?php

namespace libraries;

class Tester {
	protected $value = '';

	public function set(string $value) : void
	{
		$this->value = $value;
	}

	public function get() : string
	{
		return $this->value;
	}

}