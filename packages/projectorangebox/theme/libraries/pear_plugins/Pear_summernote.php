<?php
/**
 *
 * WYSIWYG editor
 *
 * extras are just attributes added to the textarea
 *
 * https://cdnjs.com/libraries/summernote
 * https://cdnjs.com/libraries/codemirror
 *
 * @help WYSIWYG editor
 *
 */
class Pear_summernote extends \Pear_plugin
{
	public function __construct()
	{
		ci('page')->js([
				'//cdnjs.cloudflare.com/ajax/libs/codemirror/5.12.0/codemirror.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/codemirror/5.12.0/mode/xml/xml.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.min.js',
				'/theme/orange/assets/plugins/summer-note/summernote'.PAGE_MIN.'.js',
			])->css([
				'//cdnjs.cloudflare.com/ajax/libs/codemirror/5.12.0/codemirror.min.css',
				'//cdnjs.cloudflare.com/ajax/libs/codemirror/5.12.0/theme/eclipse.css',
				'//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.min.css',
				'/theme/orange/assets/plugins/summer-note/summernote'.PAGE_MIN.'.css',
			]);
	}

	public function render($name=null, $value=null, $extra=[])
	{
		$extra['class'] .= ' form-control summernote';

		return '<textarea id="'.$name.'" name="'.$name.'" '._stringify_attributes($extra).'>'.$value.'</textarea>';
	}
}
