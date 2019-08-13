<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Q</a>
		</div>
		
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?=pear::bootstrapNav(config('nav.left'), config('nav.bootstrap nav')) ?>
			</ul>
		
			<ul class="nav navbar-nav navbar-right">
				<?php if (pear::user('has_permission', 'url::/orange_user_msgs::index~get')) { ?>
				<li>
					<a href="/admin/msgs"><i class="fa fa-envelope"></i> <span class="badge"><?=pear::user_messages(pear::user('id')) ?></span></a>
				</li>
				<?php } ?>

				<?php if (pear::user('logged_in')) { ?>
					<?=pear::bootstrapNav(config('nav.right protected'), config('nav.bootstrap nav icons'), false) ?>
				<?php } else { ?>
					<?=pear::bootstrapNav(config('nav.right public'), config('nav.bootstrap nav icons'), false) ?>
				<?php } ?>
			
			</ul>
		</div>

	</div>
</nav>
