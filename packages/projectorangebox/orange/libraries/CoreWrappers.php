<?php

/**
 * Wrapper for getting configure with dot notation from the orange config class
 * ci('config')->dot_item(...)
 *
 * @param string $setting
 * @param mixed $default
 *
 * @throws \Exception
 *
 * @return mixed
 *
 * #### Example
 * ```
 * $foo = config('file.key');
 * $foo = config('file.key2','default value');
 * ```
 */
if (!function_exists('config'))
{
	function config(string $dotNotation,/* mixed */ $default=NOVALUE) /* mixed */
	{
		$value = ci('config')->item($dotNotation, $default);

		/* only throw an error if nothing found and no default given */
		if ($value === NOVALUE) {
			throw new \Exception('The config variable "'.$dotNotation.'" is not set and no default was provided.');
		}

		return $value;
	}
}

/**
 * Wrapper for validation filters
 * This returns the filtered value
 *
 */
if (!function_exists('filter'))
{
	function filter(string $rule, $value)
	{
		/* add filter_ if it's not there */
		foreach (explode('|', $rule) as $r) {
			$a[] = 'filter_'.str_replace('filter_', '', strtolower($r));
		}

		ci('validate')->single(implode('|', $a), $value);

		return $value;
	}
}

/**
 * Wrapper for validate single
 * This return whether there validation
 * passes (true)
 * or fails (false)
 *
 */
if (!function_exists('valid'))
{
	function valid(string $rule, $field) : bool
	{
		$success = ci('validate')->group(__METHOD__)->single($rule, $field)->success(__METHOD__);

		ci('validate')->remove(__METHOD__);

		return $success;
	}
}

/**
 * Escape any single quotes with \"
 *
 * @param string $string
 *
 * @return string
 *
 */
if (!function_exists('esc'))
{
	function esc(string $string) : string
	{
		return str_replace('"', '\"', $string);
	}
}

/**
 * Escape html special characters
 *
 * @param $string
 *
 * @return string
 *
 */
if (!function_exists('e'))
{
	function e(string $input) : string
	{
		return (empty($input)) ? '' : html_escape($input);
	}
}

/**
 * End the current session and store session data.
 * (7.2 returns a boolean but prior it was null)
 * therefore we don't return anything
 *
 * @return void
 *
 */
if (!function_exists('unlockSession'))
{
	function unlockSession() : void
	{
		session_write_close();
	}
}
