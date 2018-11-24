<?php

namespace App\Http\Helpers;

use App\Resource;

class TemplateHelper
{
	public static $environment_vars = [
        'BASE_URL' => ['baseUrl' => 'The base url of the application'],
	];
	public static $resource_attrs = [
        'NAME' => ['name' => 'The name of the resource'],
        'TITLE' => ['title' => 'The title of the resource'],
        'IMAGE' => ['image' => 'The file name of the image or animated GIF'],
        'THUMB' => ['thumb' => 'The file name of the thumb nail'],
        'URL' => ['url' => 'The location of the video'],
        'TYPE' => ['type' => 'The type of the resource'],
        'TEMPLATE_ID' => ['template_id' => 'The id of the template being used for the resource'],
        'DESCRIPTION' => ['description' => 'The description of the resource'],
        'CONTENT_A' => ['content_a' => 'The copy for content A'],
        'CONTENT_B' => ['content_b' => 'The copy for content B'],
	];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public static function render(Resource $resource)
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
