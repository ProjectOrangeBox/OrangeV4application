<?php

class Pear_date_time_picker extends \Pear_plugin
{
	public function render($name=null, $value=null, $extra=[])
	{
		$extra['format'] = ($extra['format']) ? $extra['format'] : 'MM/DD/YYYY h:mm A';
		$extra['icon'] = ($extra['icon']) ? $extra['icon'] : 'calendar';

		return pear::date_picker_main($name, $value, $extra);
	}
}
