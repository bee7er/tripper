<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Guard;

use App\Model\Instance;
use App\Model\Factories\InstanceFactory;

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
		$loggedIn = false;
		$user = null;
		if ($this->auth->check()) {
			$user = \Auth::user();
			$loggedIn = true;
		}

		$tree = [];
		$controller = InstanceFactory::getControllerInstance();
		if ($controller) {
			Instance::loadChildren($controller, $tree, 0);
		}

		return view('pages.home', compact('loggedIn', 'user', 'tree'));
	}
}
