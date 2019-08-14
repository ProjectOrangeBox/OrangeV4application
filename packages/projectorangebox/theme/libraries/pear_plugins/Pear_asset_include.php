<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

/*

@help auto add page assets based on extension and default page sections

@details
pear::asset(['/folder/folder/javascript.js','/folder/folder/stylesheet.css']) will add these to the page variables
@details

*/
class Pear_asset_include extends Pear_plugin
{
	public function render($array=null)
	{
		foreach ((array)$array as $url) {
			$ext = pathinfo($url, PATHINFO_EXTENSION);

			switch ($ext) {
				case 'css':
					$html = '<link rel="stylesheet" href="'.$url.'">';
				break;
				case 'js':
					$html = '<script type="text/javascript" src="'.$url.'"></script>';
				break;
			}

			ci('page')->data('page_'.$ext,ci('load')->get_var('page_'.$ext).$html);
		}
	}
}