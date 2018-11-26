<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Guard;

use App\Model\Block;
use App\Model\Instance;
use App\Notice;
use App\Resource;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$resources = Resource::select(
			array(
			'resources.id',
			'resources.name',
			'resources.description',
			'resources.thumb',
			'resources.url',
			'resources.type',
			'resources.seq',
			'resources.deleted_at'
			)
		)
			->orderBy("resources.seq")
			->limit(999)->get();

		// Derive the hover thumbnail image and add it to the object
		foreach ($resources as &$resource) {
			$resource->hover = str_replace('th.jpg', 'hv.jpg', $resource->thumb);
		}

		// Make sure we have an even number of entries, which is a factor of 3
		$count = $resources->count();
		$count = (($count % 2) !== 0) ? ($count + 1): $count;

		$first = null;
		$useImage = 0;
		while (($count % 3) !== 0) {
			$use = clone($resources->get($useImage));
			$use['id'] = (9999 + $useImage);		// Dummy unique id
			$resources = $resources->merge([$use]);
			$count = $resources->count();
			$useImage++;
		}

		$notices = Notice::select(
			array(
				'notices.id',
				'notices.seq',
				'notices.notice',
				'notices.url',
				'notices.deleted_at'
			)
		)
			->orderBy("notices.seq")
			->limit(999)->get();

		$loggedIn = false;
		$user = null;
		if ($this->auth->check()) {
			$user = \Auth::user();
			$loggedIn = true;
		}

		$user = null;
		$loggedIn = false;
		if ($this->auth->check()) {
			$user = \Auth::user();
			$loggedIn = true;
		}

		$tree = [];
		$controller = Instance::getController(1);
		if ($controller) {
			Instance::loadChildren($controller, $tree, 0);
		}

		return view('pages.home', compact('resources', 'notices', 'loggedIn', 'user', 'tree'));
	}
}
