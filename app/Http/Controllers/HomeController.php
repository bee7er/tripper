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
		$controller = Instance::getController();
		if ($controller) {
			$this->loadChildren($controller, $tree, 0);
		}

		return view('pages.home', compact('resources', 'notices', 'loggedIn', 'user', 'tree'));
	}

	public function loadChildren($instance, &$tree, $depth)
	{
		$depth++;
		if ($instance) {
			$children = Instance::getChildren($instance->id);
			$len = count($children);
			for ($i=0; $i<$len; $i++) {
				$child = $children[$i];
				$nextChild = isset($children[$i + 1]) ? $children[$i + 1] : null;
				$nextBlock = null;
				if ($nextChild) {
					$nextBlock = Block::getBlock($nextChild->block_id);
				}

				$block = Block::getBlock($child->block_id);

				$tree[$child->id . ''] = (str_repeat('| ', $depth - 1) . $block->top1 . $block->top2 . ' ' . $block->type . ': ' . $child->title);

				//dd($block);
				if ($block->container) {
					$this->loadChildren($child, $tree, $depth);

					if ($nextBlock && $nextBlock->type == Block::BLOCK_TYPE_ELSE) {
						// Do not include an end entry because the condition continues
					} elseif ($block->type == Block::BLOCK_TYPE_ELSE) {
						$tree[$child->id . 'e'] = (str_repeat('| ', $depth - 1) . $block->bottom1 . $block->bottom2 . Block::BLOCK_TYPE_CONDITION . ': End ' . $child->title);
					} else {
						$tree[$child->id . 'e'] = (str_repeat('| ', $depth - 1) . $block->bottom1 . $block->bottom2 . $block->type . ': End ' . $child->title);
					}
				}
			}
		}
	}
}
