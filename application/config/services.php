<?php
/**
 * Since we are using a service locator pattern this is where the services are loaded.
 * This of course can be overridden to mock services as need
 */

return [
	'exceptions'=>'\projectorangebox\orange\library\Exceptions',
	'benchmark'=>'\projectorangebox\orange\library\Benchmark',
	'hooks'=>'\projectorangebox\orange\library\Hooks',
	'utf8'=>'\projectorangebox\orange\library\Utf8',
	'uri'=>'\projectorangebox\orange\library\Uri',
	'security'=>'\projectorangebox\orange\library\Security',
	'lang'=>'\projectorangebox\orange\library\Lang',
	'loader'=>'\projectorangebox\orange\library\Loader',

	'auth'=>'\projectorangebox\orange\library\Auth',
	'cache'=>'\projectorangebox\orange\library\Cache',
	'errors'=>'\projectorangebox\orange\library\Errors',
	'event'=>'\projectorangebox\orange\library\Event',
	'page'=>'\projectorangebox\orange\library\Page',
	'validate'=>'\projectorangebox\orange\library\Validate',
	'wallet'=>'\projectorangebox\orange\library\Wallet',
	'session'=>'\projectorangebox\orange\library\Session',

	'config'=>'\projectorangebox\orange\library\Config',
	'log'=>'\projectorangebox\orange\library\Log',
	'router'=>'\projectorangebox\orange\library\Router',
	'output'=>'\projectorangebox\orange\library\Output',
	'input'=>'\projectorangebox\orange\library\Input',

	'o_user_model'=>'\projectorangebox\orange\model\O_user_model',

	'example_model'=>'\projectorangebox\theme\model\Example_model',

	'cache_driver_apc' => '\projectorangebox\orange\library\cache\Apc',
	'cache_driver_dummy' => '\projectorangebox\orange\library\cache\Dummy',
	'cache_driver_file' => '\projectorangebox\orange\library\cache\File',
	'cache_driver_memcached' => '\projectorangebox\orange\library\cache\Memcached',
	'cache_driver_redis' => '\projectorangebox\orange\library\cache\Redis',
	'cache_driver_wincache' => '\projectorangebox\orange\library\cache\Wincache',
	'cache_driver_export' => '\projectorangebox\orange\library\cache\Export',
	'cache_driver_request' => '\projectorangebox\orange\library\cache\Request',

	'filter_input' => '\projectorangebox\orange\library\validate\filters\Input',
	'filter_slug' => '\projectorangebox\orange\library\validate\filters\Slug',
	'filter_str' => '\projectorangebox\orange\library\validate\filters\Str',
	'filter_human' => '\projectorangebox\orange\library\validate\filters\Human',
	'filter_visible' => '\projectorangebox\orange\library\validate\filters\Visible',
	'filter_textarea' => '\projectorangebox\orange\library\validate\filters\Textarea',
	'filter_filename' => '\projectorangebox\orange\library\validate\filters\Filename',
	'filter_integer' => '\projectorangebox\orange\library\validate\filters\Integer',
	'filter_length' => '\projectorangebox\orange\library\validate\filters\Length',
	'filter_convert_date' => '\projectorangebox\orange\library\validate\filters\Convert_date',
	'filter_number' => '\projectorangebox\orange\library\validate\filters\Number',

	'pear_include' => '\projectorangebox\orange\library\pear_plugins\Pear_include',
	'pear_section' => '\projectorangebox\orange\library\pear_plugins\Pear_section',
	'pear_plugins' => '\projectorangebox\orange\library\pear_plugins\Pear_plugins',
	'pear_end' => '\projectorangebox\orange\library\pear_plugins\Pear_end',
	'pear_extends' => '\projectorangebox\orange\library\pear_plugins\Pear_extends',
	'pear_page' => '\projectorangebox\orange\library\pear_plugins\Pear_page',
	'pear_parent' => '\projectorangebox\orange\library\pear_plugins\Pear_parent',
	'pear_goback_button' => '\projectorangebox\theme\library\pear_plugins\Pear_goback_button',
	'pear_date_picker' => '\projectorangebox\theme\library\pear_plugins\Pear_date_picker',
	'pear_date' => '\projectorangebox\theme\library\pear_plugins\Pear_date',
	'pear_bootbox' => '\projectorangebox\theme\library\pear_plugins\Pear_bootbox',
	'pear_role_permission' => '\projectorangebox\theme\library\pear_plugins\Pear_role_permission',
	'pear_combobox' => '\projectorangebox\theme\library\pear_plugins\Pear_combobox',
	'pear_sortable' => '\projectorangebox\theme\library\pear_plugins\Pear_sortable',
	'pear_header_button' => '\projectorangebox\theme\library\pear_plugins\Pear_header_button',
	'pear_bootstrap_wysiwyg' => '\projectorangebox\theme\library\pear_plugins\Pear_bootstrap_wysiwyg',
	'pear_table_sticky_header' => '\projectorangebox\theme\library\pear_plugins\Pear_table_sticky_header',
	'pear_field_range' => '\projectorangebox\theme\library\pear_plugins\Pear_field_range',
	'pear_color_value' => '\projectorangebox\theme\library\pear_plugins\Pear_color_value',
	'pear_select3' => '\projectorangebox\theme\library\pear_plugins\Pear_select3',
	'pear_bootstrapnav' => '\projectorangebox\theme\library\pear_plugins\Pear_bootstrapNav',
	'pear_color_picker' => '\projectorangebox\theme\library\pear_plugins\Pear_color_picker',
	'pear_html_tag' => '\projectorangebox\theme\library\pear_plugins\Pear_html_tag',
	'pear_echo' => '\projectorangebox\orange\library\pear_plugins\Pear_echo',
	'pear_nestable' => '\projectorangebox\theme\library\pear_plugins\Pear_nestable',
	'pear_table_sort' => '\projectorangebox\theme\library\pear_plugins\Pear_table_sort',
	'pear_confirm_dialog' => '\projectorangebox\theme\library\pear_plugins\Pear_confirm_dialog',
	'pear_field_label' => '\projectorangebox\theme\library\pear_plugins\Pear_field_label',
	'pear_color_block' => '\projectorangebox\theme\library\pear_plugins\Pear_color_block',
	'pear_fa_dropdown' => '\projectorangebox\theme\library\pear_plugins\Pear_fa_dropdown',
	'pear_back_to_top' => '\projectorangebox\theme\library\pear_plugins\Pear_back_to_top',
	'pear_html_list' => '\projectorangebox\theme\library\pear_plugins\Pear_html_list',
	'pear_flash_msg' => '\projectorangebox\theme\library\pear_plugins\Pear_flash_msg',
	'pear_fa_icon' => '\projectorangebox\theme\library\pear_plugins\Pear_fa_icon',
	'pear_asset_route' => '\projectorangebox\theme\library\pear_plugins\Pear_asset_route',
	'pear_new_button' => '\projectorangebox\theme\library\pear_plugins\Pear_new_button',
	'pear_menu_li' => '\projectorangebox\theme\library\pear_plugins\Pear_menu_li',
	'pear_color_fa_icon' => '\projectorangebox\theme\library\pear_plugins\Pear_color_fa_icon',
	'pear_input_mask' => '\projectorangebox\theme\library\pear_plugins\Pear_input_mask',
	'pear_money' => '\projectorangebox\theme\library\pear_plugins\Pear_money',
	'pear_checker' => '\projectorangebox\theme\library\pear_plugins\Pear_checker',
	'pear_role_dropdown' => '\projectorangebox\theme\library\pear_plugins\Pear_role_dropdown',
	'pear_textarea' => '\projectorangebox\theme\library\pear_plugins\Pear_textarea',
	'pear_field_human' => '\projectorangebox\theme\library\pear_plugins\Pear_field_human',
	'pear_title' => '\projectorangebox\theme\library\pear_plugins\Pear_title',
	'pear_tab_id' => '\projectorangebox\theme\library\pear_plugins\Pear_tab_id',
	'pear_keymaster' => '\projectorangebox\theme\library\pear_plugins\Pear_keymaster',
	'pear_sprintf' => '\projectorangebox\theme\library\pear_plugins\Pear_sprintf',
	'pear_delete_button' => '\projectorangebox\theme\library\pear_plugins\Pear_delete_button',
	'pear_e' => '\projectorangebox\theme\library\pear_plugins\Pear_e',
	'pear_user' => '\projectorangebox\theme\library\pear_plugins\Pear_user',
	'pear_table_remember_position' => '\projectorangebox\theme\library\pear_plugins\Pear_table_remember_position',
	'pear_datalist' => '\projectorangebox\theme\library\pear_plugins\Pear_datalist',
	'pear_bound_table_search_field' => '\projectorangebox\theme\library\pear_plugins\Pear_bound_table_search_field',
	'pear_date_time_picker' => '\projectorangebox\theme\library\pear_plugins\Pear_date_time_picker',
	'pear_summernote' => '\projectorangebox\theme\library\pear_plugins\Pear_summernote',
	'pear_form_help' => '\projectorangebox\theme\library\pear_plugins\Pear_form_help',
	'pear_index_row_button' => '\projectorangebox\theme\library\pear_plugins\Pear_index_row_button',
	'pear_example_close' => '\projectorangebox\theme\library\pear_plugins\Pear_example_close',
	'pear_form_static' => '\projectorangebox\theme\library\pear_plugins\Pear_form_static',
	'pear_edit_button' => '\projectorangebox\theme\library\pear_plugins\Pear_edit_button',
	'pear_time_picker' => '\projectorangebox\theme\library\pear_plugins\Pear_time_picker',
	'pear_table_search_field' => '\projectorangebox\theme\library\pear_plugins\Pear_table_search_field',
	'pear_asset_include' => '\projectorangebox\theme\library\pear_plugins\Pear_asset_include',
	'pear_locked_field' => '\projectorangebox\theme\library\pear_plugins\Pear_locked_field',
	'pear_example_open' => '\projectorangebox\theme\library\pear_plugins\Pear_example_open',
	'pear_wrap' => '\projectorangebox\theme\library\pear_plugins\Pear_wrap',
	'pear_uri' => '\projectorangebox\theme\library\pear_plugins\Pear_uri',
	'pear_asset' => '\projectorangebox\theme\library\pear_plugins\Pear_asset',
	'pear_notify' => '\projectorangebox\theme\library\pear_plugins\Pear_notify',
	'pear_tab_prepare' => '\projectorangebox\theme\library\pear_plugins\Pear_tab_prepare',
	'pear_fa_enum_icon' => '\projectorangebox\theme\library\pear_plugins\Pear_fa_enum_icon',
	'pear_tabs' => '\projectorangebox\theme\library\pear_plugins\Pear_tabs',
	'pear_form_helpers' => '\projectorangebox\theme\library\pear_plugins\Pear_form_helpers',
	'pear_rest_form' => '\projectorangebox\theme\library\pear_plugins\Pear_rest_form',
	'pear_tab_title' => '\projectorangebox\theme\library\pear_plugins\Pear_tab_title',
	'pear_tab_save' => '\projectorangebox\theme\library\pear_plugins\Pear_tab_save',
	'pear_catalog_dropdown' => '\projectorangebox\theme\library\pear_plugins\Pear_catalog_dropdown',
	'pear_date_picker_main' => '\projectorangebox\theme\library\pear_plugins\Pear_date_picker_main',
	'pear_catalog_lookup' => '\projectorangebox\theme\library\pear_plugins\Pear_catalog_lookup',

	'validation_required' => '\projectorangebox\orange\library\validate\rules\Required',
	'validation_is_natural' => '\projectorangebox\orange\library\validate\rules\Is_natural',
	'validation_in_list' => '\projectorangebox\orange\library\validate\rules\In_list',
	'validation_exact_length' => '\projectorangebox\orange\library\validate\rules\Exact_length',
	'validation_max_length' => '\projectorangebox\orange\library\validate\rules\Max_length',
	'validation_valid_url' => '\projectorangebox\orange\library\validate\rules\Valid_url',
	'validation_valid_ip' => '\projectorangebox\orange\library\validate\rules\Valid_ip',
	'validation_alpha_numeric_spaces' => '\projectorangebox\orange\library\validate\rules\Alpha_numeric_spaces',
	'validation_regex_match' => '\projectorangebox\orange\library\validate\rules\Regex_match',
	'validation_differs' => '\projectorangebox\orange\library\validate\rules\Differs',
	'validation_is_unique' => '\projectorangebox\orange\library\validate\rules\Is_unique',
	'validation_alpha' => '\projectorangebox\orange\library\validate\rules\Alpha',
	'validation_alpha_space' => '\projectorangebox\orange\library\validate\rules\Alpha_space',
	'validation_greater_than_equal_to' => '\projectorangebox\orange\library\validate\rules\Greater_than_equal_to',
	'validation_greater_than' => '\projectorangebox\orange\library\validate\rules\Greater_than',
	'validation_decimal' => '\projectorangebox\orange\library\validate\rules\Decimal',
	'validation_less_than_equal_to' => '\projectorangebox\orange\library\validate\rules\Less_than_equal_to',
	'validation_is_natural_no_zero' => '\projectorangebox\orange\library\validate\rules\Is_natural_no_zero',
	'validation_integer' => '\projectorangebox\orange\library\validate\rules\Integer',
	'validation_less_than' => '\projectorangebox\orange\library\validate\rules\Less_than',
	'validation_numeric' => '\projectorangebox\orange\library\validate\rules\Numeric',
	'validation_valid_emails' => '\projectorangebox\orange\library\validate\rules\Valid_emails',
	'validation_valid_base64' => '\projectorangebox\orange\library\validate\rules\Valid_base64',
	'validation_valid_email' => '\projectorangebox\orange\library\validate\rules\Valid_email',
	'validation_matches' => '\projectorangebox\orange\library\validate\rules\Matches',
	'validation_alpha_numeric' => '\projectorangebox\orange\library\validate\rules\Alpha_numeric',
	'validation_has_errors' => '\projectorangebox\orange\library\validate\rules\Has_errors',
	'validation_min_length' => '\projectorangebox\orange\library\validate\rules\Min_length',
	'validation_alpha_dash' => '\projectorangebox\orange\library\validate\rules\Alpha_dash',

	'#form/test' => '/packages/projectorangebox/theme/views/form/test.php',
	'#_templates/orange_admin' => '/packages/projectorangebox/theme/views/_templates/orange_admin.php',
	'#_templates/access' => '/packages/projectorangebox/theme/views/_templates/access.php',
	'#_templates/nav' => '/packages/projectorangebox/theme/views/_templates/nav.php',
	'#_templates/footer' => '/packages/projectorangebox/theme/views/_templates/footer.php',
	'#_templates/blank' => '/packages/projectorangebox/theme/views/_templates/blank.php',
	'#_templates/header' => '/packages/projectorangebox/theme/views/_templates/header.php',
	'#_templates/orange_default' => '/packages/projectorangebox/theme/views/_templates/orange_default.php',
	'#cats' => '/packages/projectorangebox/theme/views/cats.php',
	'#form' => '/application/views/form.php',
	'#main/welcome' => '/application/views/welcome/index.php',
	'#welcome_message' => '/application/views/welcome_message.php',
	'#errors/html/error_db' => '/application/views/errors/html/error_db.php',
	'#errors/html/error_404' => '/application/views/errors/html/error_404.php',
	'#errors/html/error_php' => '/application/views/errors/html/error_php.php',
	'#errors/html/error_exception' => '/application/views/errors/html/error_exception.php',
	'#errors/html/error_general' => '/application/views/errors/html/error_general.php',
	'#errors/cli/error_db' => '/application/views/errors/cli/error_db.php',
	'#errors/cli/error_404' => '/application/views/errors/cli/error_404.php',
	'#errors/cli/error_php' => '/application/views/errors/cli/error_php.php',
	'#errors/cli/error_exception' => '/application/views/errors/cli/error_exception.php',
	'#errors/cli/error_general' => '/application/views/errors/cli/error_general.php',

];
