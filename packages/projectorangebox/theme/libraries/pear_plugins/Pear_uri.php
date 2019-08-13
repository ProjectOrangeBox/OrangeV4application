<?php

// @help The URI extension is designed to make URI checks within templates easier. http://platesphp.com/v3/extensions/uri/

class Pear_uri extends \Pear_plugin
{
	protected $uri;
	protected $parts;

	public function render($var1 = null, $var2 = null, $var3 = null, $var4 = null)
	{
		$this->uri = '/'.ci('uri')->uri_string();
		$this->parts = explode('/', $this->uri);

		if (is_null($var1)) {
			return $this->uri;
		}

		if (is_numeric($var1) and is_null($var2)) {
			return array_key_exists($var1, $this->parts) ? $this->parts[$var1] : null;
		}

		if (is_numeric($var1) and is_string($var2)) {
			return $this->checkUriSegmentMatch($var1, $var2, $var3, $var4);
		}

		if (is_string($var1)) {
			return $this->checkUriRegexMatch($var1, $var2, $var3);
		}
	}

	protected function checkUriSegmentMatch($key, $string, $returnOnTrue = null, $returnOnFalse = null)
	{
		if (array_key_exists($key, $this->parts) && $this->parts[$key] === $string) {
			return is_null($returnOnTrue) ? true : $returnOnTrue;
		}

		return is_null($returnOnFalse) ? false : $returnOnFalse;
	}

	protected function checkUriRegexMatch($regex, $returnOnTrue = null, $returnOnFalse = null)
	{
		if (preg_match('#^'.$regex.'$#', $this->uri) === 1) {
			return is_null($returnOnTrue) ? true : $returnOnTrue;
		}

		return is_null($returnOnFalse) ? false : $returnOnFalse;
	}
}
