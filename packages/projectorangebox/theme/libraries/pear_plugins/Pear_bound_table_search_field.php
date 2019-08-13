<?php

class Pear_bound_table_search_field extends \Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')->js('/theme/orange/assets/plugins/bound-table-search/bound-table-search'.PAGE_MIN.'.js');
		}
	}

	public function render($options=[])
	{
		$id = (isset($options['id'])) ? $options['id'] : 'bound-table-search-field';
		$length = (isset($options['length'])) ? $options['length'] : 222;
		$url = (isset($options['url'])) ? $options['url'] : false;
		$placeholder = (isset($options['placeholder'])) ? $options['placeholder'] : 'search';

		$extra = ($url) ? 'data-url="'.$url.'" ' : '';

		return '<div class="form-group has-feedback" style="display:inline-block">
		<input type="text" id="'.$id.'" class="form-control input-sm" '.$extra.'style="width:'.$length.'px;" placeholder="'.$placeholder.'">
		<i class="fa fa-search form-control-feedback"></i>
		</div>';
	}
}
