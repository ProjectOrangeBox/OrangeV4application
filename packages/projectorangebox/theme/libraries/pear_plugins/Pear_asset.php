<?php
/*

@help auto asset url generator

@details
pear::asset('/folder/folder/image.png') will return <img src="/folder/folder/image.png">
pear::asset('/folder/folder/script.js') will return <script type="text/javascript" src="/folder/folder/script.js"></script>
pear::asset('/folder/folder/styles.css') will return <link rel="stylesheet" href="/folder/folder/styles.css">

pear::asset('/folder/folder/styles.css',['extra'=>'foobar']) will return <link rel="stylesheet" href="/folder/folder/styles.css" extra="foobar">
pear::asset('/folder/folder/styles.css',['extra'=>'foobar']) will return <link rel="stylesheet" href="/folder/folder/styles.css" extra="foobar">
@details

*/
class Pear_asset extends \Pear_plugin
{
	public function render($url=null, $attributes=null)
	{
		$html = '';

		switch (pathinfo($url, PATHINFO_EXTENSION)) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
				$html = '<img src="'.$url.'"'.$this->attributes($attributes).'>';
			break;
			case 'css':
				$html = ($attributes === true) ? '<style>'.$this->get_content($url).'</style>' : '<link rel="stylesheet" href="'.$url.'"'.$this->attributes($attributes).'>';
			break;
			case 'js':
				$html = ($attributes === true) ? '<script type="text/javascript">'.$this->get_content($url).'</script>' : '<script type="text/javascript" src="'.$url.'"'.$this->attributes($attributes).'></script>';
			break;
		}

		return $html;
	}

	protected function get_content($url)
	{
		$file_path = WWW.$url;

		return (file_exists($file_path)) ? file_get_contents($file_path) : '<!-- '.$url.' not found -->';
	}

	protected function attributes($attributes)
	{
		$attr = '';

		if (is_string($attributes)) {
			$attr = ' '.$attributes;
		} elseif (is_array($attributes)) {
			foreach ($attributes as $key=>$val) {
				$attr .= ' '.$key.'="'.$val.'"';
			}
		}

		return $attr;
	}
}
