<?php

namespace projectorangebox\theme\library\pear_plugins;

use projectorangebox\orange\library\abstracts\Pear_plugin;

/*
Drag and Drop Sortable

https://github.com/RubaXa/Sortable#cdn

<div id="sortable" class="list-group">
	<div class="list-group-item"></div>
	<div class="list-group-item"></div>
	<div class="list-group-item"></div>
	<div class="list-group-item"></div>
	<div class="list-group-item"></div>
</div>

<?=pear::sortable('sortable') ?>
<div id="sortable" class="list-group">
	<? foreach ($repeatable as $record) { ?>
		<?=pear::include('test/repeatable',['parent_id'=>$id,'id'=>$record['id'],'firstname'=>$record['firstname'],'lastname'=>$record['lastname']]) ?>
	<? } ?>
</div>

*/
class Pear_sortable extends Pear_plugin
{
	public function __construct()
	{
		if (config('page.usingCDNs')) {
			ci('page')->js('//cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js');
		}
	}

	public function render($id=null)
	{
		ci('page')->domready('Sortable.create('.$id.',{});');
	}
}
