<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

/**
 *
 * ComboBox
 *
 * pear::datalist($name,$value,$options,$extras)
 *
 * there are no extras supported by datalist
 * so this is currently just passed to pear::dropdown(...)
 *
 */
 class Pear_datalist extends Pear_plugin
 {
	 public function __construct()
	 {
		if (config('page.usingCDNs')) {
		 ci('page')
			->js('//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js')
			->css('//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css');
		}

		ci('page')->domready("$('.datalist').editableSelect({effects:'fade'});");
	 }

	 public function render($name='', $value='', $options=[], $extras=[])
	 {
		 $extras['class'] .= ' datalist';

		 if (!array_key_exists($value, $options)) {
			 $options[$value] = $value;
		 }

		 return pear::dropdown($name, $options, $value, $extras);
	 }
 }
