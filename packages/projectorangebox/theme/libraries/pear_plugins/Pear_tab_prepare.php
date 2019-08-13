<?php

class Pear_tab_prepare extends \Pear_plugin
{
	public function render($records=null, $key=null, $sort_key=null)
	{
		$tabs = [];

		foreach ($records as $row) {
			if (is_object($row)) {
				$tabs[$row->$key][$row->$sort_key] = $row;
			} elseif (is_array($row)) {
				$tabs[$row[$key]][$row[$sort_key]] = $row;
			} else {
				throw new exception('Pear Tab Prepare records not a object or array.',404);
			}
		}

		ksort($tabs);

		foreach ($tabs as $idx=>$ary) {
			ksort($ary);
			$tabs[$idx] = $ary;
		}

		return $tabs;
	}
}
