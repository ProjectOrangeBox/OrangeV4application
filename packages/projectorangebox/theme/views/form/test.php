<?php pear::extends('_templates/orange_default') ?>

<?php pear::section('section_container') ?>
<div class="row">
  <div class="col-md-6"><?=pear::title('Dashboard', 'th') ?></div>
  <div class="col-md-6"></div>
</div>

<div class="row metro"></div>
<?php pear::end() ?>

<?php pear::section('page_body_class') ?>dashboard<?php pear::parent() ?><?php pear::end() ?>

<?php pear::section('page_style') ?>
.dashboard a.btn.btn-lg.btn-block {
	transition:all 0.3s ease;
  opacity:0.9;
  color: white;
	white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.dashboard a.btn.btn-lg.btn-block:hover {
  -webkit-transform: scale(1.05);
  -ms-transform: scale(1.05);
  transform: scale(1.02);
  opacity:1;
  color: #eee;
}
<?php pear::end() ?>

<?php pear::section('page_script') ?>
document.addEventListener("DOMContentLoaded", function(event) {
	$('#navbar ul.dropdown-menu li a').each(function() {
		var parent_text = $(this).closest('.dropdown').find('a:first').text();
		var href = $(this).attr('href');
		var target = $(this).attr('target');
		var icon = $(this).data('icon');
		var color = $(this).data('color');
		var text = $(this).text();

		icon = (icon) ? icon : 'link';
		color = (color) ? color : 'E36B2A';
		target = (target) ? ' target="'+target+'" ' : '';

		if (href != '#' && href != '') {
			$('div.metro').append('<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 col-margin-tb"><a href="'+href+'" '+target+' class="btn btn-lg btn-block" style="background-color: #'+color+'"><i class="fa fa-'+icon+' fa-2x" aria-hidden="true"></i><br>'+parent_text+'<br>'+text+'</a></div>');
		}
	});
});
<?php pear::end() ?>
