<div class="btn-group btn-group-sm">
	<a class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="" data-original-title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a data-edit="fontName Times" style="font-family:Times">Times</a></li>
		<li><a data-edit="fontName Helvetica" style="font-family:Helvetica">Helvetica</a></li>
		<li><a data-edit="fontName Courier" style="font-family:Courier">Courier</a></li>
	</ul>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="" data-original-title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a data-edit="fontSize 6"><font size="6">xxlarge</font></a></li>
			<li><a data-edit="fontSize 5"><font size="5">xlarge</font></a></li>
			<li><a data-edit="fontSize 4"><font size="4">large</font></a></li>
			<li><a data-edit="fontSize 3"><font size="3">medium</font></a></li>
			<li><a data-edit="fontSize 2"><font size="2">small</font></a></li>
			<li><a data-edit="fontSize 1"><font size="1">xsmall</font></a></li>
		</ul>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default dropdown-toggle btn-default" data-toggle="dropdown" title="Font Size"><i class="fa fa-tint"></i>&nbsp;<b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a data-edit="foreColor #ac4142" title=""><i style="color: #ac4142;" class="fa fa-tint"></i> Red</a></li>
		<li><a data-edit="foreColor #90a959" title=""><i style="color: #90a959;" class="fa fa-tint"></i> Green</a></li>
		<li><a data-edit="foreColor #0673B6" title=""><i style="color: #0673B6;" class="fa fa-tint"></i> Blue</a></li>
		<li><a data-edit="foreColor #FEF152" title=""><i style="color: #FEF152;" class="fa fa-tint"></i> Yellow</a></li>
		<li><a data-edit="foreColor #000" title=""><i style="color: #000;" class="fa fa-tint"></i> Black</a></li>
		<li><a data-edit="foreColor #333" title=""><i style="color: #333;" class="fa fa-tint"></i> None</a></li>
	</ul>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default" data-edit="bold" title="" data-original-title="Bold"><i class="fa fa-bold"></i></a>
	<a class="btn btn-default" data-edit="italic" title="" data-original-title="Italic"><i class="fa fa-italic"></i></a>
	<a class="btn btn-default" data-edit="strikethrough" title="" data-original-title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
	<a class="btn btn-default" data-edit="underline" title="" data-original-title="Underline"><i class="fa fa-underline"></i></a>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default js-link"><i class="fa fa-chain"></i></a>
	<a class="btn btn-default" data-edit="unlink" title="" data-original-title="Remove Hyperlink"><i class="fa fa-chain-broken"></i></a>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default" data-edit="insertunorderedlist" title="" data-original-title="Bullet list"><i class="fa fa-list-ul"></i></a>
	<a class="btn btn-default" data-edit="insertorderedlist" title="" data-original-title="Number list"><i class="fa fa-list-ol"></i></a>
	<a class="btn btn-default" data-edit="outdent" title="" data-original-title="Reduce indent"><i class="fa fa-outdent"></i></a>
	<a class="btn btn-default" data-edit="indent" title="" data-original-title="Indent"><i class="fa fa-indent"></i></a>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default" data-edit="justifyleft" title="" data-original-title="Align Left"><i class="fa fa-align-left"></i></a>
	<a class="btn btn-default" data-edit="justifycenter" title="" data-original-title="Center"><i class="fa fa-align-center"></i></a>
	<a class="btn btn-default" data-edit="justifyright" title="" data-original-title="Align Right"><i class="fa fa-align-right"></i></a>
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default btn-sm" title="" id="pictureBtn" data-original-title="Insert picture"><i class="fa fa-picture-o"></i></a>
	<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 36px; height: 30px;">
</div>
<div class="btn-group btn-group-sm">
	<a class="btn btn-default" data-edit="removeFormat" title="" data-original-title="Remove Style"><i class="fa fa-eraser"></i></a>
	<a class="btn btn-default" data-edit="insertHorizontalRule" title="" data-original-title="Horizontal Rule">&horbar;</a>
	<?php if (auth::access('CMS Pages::Edit Source')) {
	?>
	<a class="btn js-wysiwyg-source btn-default" title="" data-original-title="Source"><i class="fa fa-file-code-o"></i></a>
	<?php
} ?>
</div>
