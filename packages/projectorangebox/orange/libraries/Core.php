<?php

/**
 * ci
 *
 * @param mixed string service name to load
 * @param mixed string when attaching it to the CodeIgniter super object attach it as
 * @return mixed
 */
if (!function_exists('ci'))
{
	function ci(string $name = null, string $as = null) /* mixed */
	{
		/* get a instance of CodeIgniter */
		$instance = get_instance();

		/* if the name has segments (namespaced or folder based) we only need the last which is the service name */
		$serviceName = ($as) ?? basename(str_replace('\\','/',$name),'.php');

		if ($serviceName) {
			/* has this service been attached yet? */
			if (!isset($instance->$serviceName)) {
				/* is it a named service? */
				$config = loadConfig('services');

				if (isset($config['services'][$name])) {
					$name = $config['services'][$name];
				}

				/* try to let composer autoload load it */
				if (class_exists($name,true)) {
					/* load a matching config if it exists */
					/* create a new instance and attach the singleton to the CodeIgniter super object */
					$instance->$serviceName = new $name(loadConfig($serviceName));
				} else {
					/* else try to let CodeIgniter load it the old fashion way */
					if (substr($name,-6) == '_model') {
						$instance->load->model($name,$serviceName);
					} else {
						/* library will take a config so let's try to find it if it exists */
						$instance->load->library($name,loadConfig($serviceName));
					}
				}
			}

			/* now grab the reference */
			$instance = $instance->$serviceName;
		}

		return $instance;
	}
}

/* override the CodeIgniter loader to use composer */
if (!function_exists('load_class'))
{
	function &load_class(string $class)
	{
		static $_classes = [];

		if (isset($_classes[$class])) {
			return $_classes[$class];
		}

		$name = false;
		$ci_prefix = 'CI_';
		$subclass_prefix = config_item('subclass_prefix');

		if (class_exists($subclass_prefix.ucfirst($class),true)) {
			$name = $subclass_prefix.ucfirst($class);
		} elseif (class_exists($ci_prefix.ucfirst($class),true)) {
			$name = $ci_prefix.ucfirst($class);
		}

		if (!$name) {
			set_status_header(503);
			throw new \Exception('Unable to locate the specified class: "'.$class.'.php"');
		}

		is_loaded($class);

		$_classes[$class] = new $name();

		return $_classes[$class];
	}
}

/**
 *
 * Orange Assertion Handler
 *
 * @param $file
 * @param $line
 * @param $code
 * @param $desc
 *
 * @return void
 *
 */
if (!function_exists('_assert_handler'))
{
	function _assert_handler($file, $line, $code, $desc='') : void
	{
		/* CLI */
		if (defined('STDIN')) {
			echo json_encode(['file'=>$file,'line'=>$line,'description'=>$desc], JSON_PRETTY_PRINT);

		/* AJAX */
		} elseif (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			echo json_encode(['file'=>$file,'line'=>$line,'description'=>$desc], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);

		/* HTML */
		} else {
			echo '<!doctype html><title>Assertion Failed</title>';
			echo '<style>body, html { text-align: center; padding: 150px; background-color: #492727; font: 20px Helvetica, sans-serif; color: #fff; font-size: 18px;}h1 { font-size: 150%; }article { display: block; text-align: left; width: 650px; margin: 0 auto; }</style>';
			echo '<article><h1>Assertion Failed</h1>	<div><p>File: '.$file.'</p><p>Line: '.$line.'</p><p>Code: '.$code.'</p><p>Description: '.$desc.'</p></div></article>';
		}

		exit(1);
	}
}

require_once 'Core_Global_Functions.php';

require_once BASEPATH.'core/CodeIgniter.php';
