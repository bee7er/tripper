<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;
use App\Model\Instance;

class ActionDiagramController extends AdminController
{
    /**
     * ActionDiagramController constructor.
     */
    public function __construct()
    {
        view()->share('type', 'actionDiagram');
    }

    /**
     * Show a list of all the trip posts.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $tree = [];
        $controller = Instance::getController($request->trip->id);
        if ($controller) {
            Instance::loadChildren($controller, $tree);
        }

        $tripTitle = $request->trip->title;

        // Show the page
        return view('admin.actionDiagram.index', compact('tree', 'tripTitle'));
    }
}
