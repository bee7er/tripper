<?php namespace App\Http\Controllers\Admin;

use App\Model\ContextMenu;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;
use App\Model\Instance;
use Illuminate\Support\Facades\Response;

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
        $trip = $request->trip;

        // Show the page
        return view('admin.actionDiagram.index', compact('tree', 'trip'));
    }

    /**
     * Build and return the form for editing an object
     *
     * @return Response
     */
    public function getActionDiagram()
    {
        $success = true;
        $tripId = Input::get('tripId');
        if (!$tripId) {
            $success = false;
            $formHtml = 'Error, trip id not found in function parameters';
        } else {
            $trip = Trip::find($tripId);
            if (!$trip) {
                $success = false;
                $formHtml = "Error, could not find trip for id $tripId";
            } else {
                $tree = [];
                $controller = Instance::getController($tripId);
                if ($controller) {
                    Instance::loadChildren($controller, $tree);
                }

                $formHtml = '<div id="dig" class="row-container" style="border: 1px solid #c4c4c4;">';
                $formHtml .= '<div class="row" style="text-align: left;margin: 0;padding: 8px;">';
                if (count($tree) > 0) {
                    foreach($tree as $twig) {
                        foreach($twig->entries as $entry) {
                            $formHtml .= '<div class="row-selected" id="' . $twig->id . '">' . $entry . '</div >';
                        }
                    }
                }
                $formHtml .= '</div></div>';
            }
        }

        return Response::json(array(
            'success' => $success,
            'formHtml'   => $formHtml
        ));
    }
}
