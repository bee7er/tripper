<?php

namespace App\Http\Helpers;

use App\Resource;

class ViewHelper
{
	/**
	 * Render an action diagram entry
	 *
	 * @return string
	 */
	public static function render()
	{
		if ($template = $resource->template()->first()) {

			$container = $template->container;
			if ($container) {
				// Gather all potential environment variables we are supporting
				$baseUrl = config('app.base_url');
				// Now substitute in the container
				foreach (self::$environment_vars as $environment_var => $var) {
					$key = key($var);
					$container =
						str_ireplace("#$environment_var#", $$key, $container);
				}
				// Now substitute resource attributes in the container
				foreach (self::$resource_attrs as $resource_attr => $attr) {
					$key = key($attr);
					$container =
						str_ireplace("#$resource_attr#", $resource->$key, $container);
				}
				return $container;
			}
		}
		return '';
	}
}
