<?php namespace App\Http\Controllers\Admin;

use App\Model\ContextMenu;
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
        $tree = [];
        $controller = Instance::getController($request->trip->id);
        if ($controller) {
            Instance::loadChildren($controller, $tree);
        }

        $tripTitle = $request->trip->title;

        // Show the page
        return view('admin.actionDiagram.index', compact('tree', 'tripTitle'));
    }

    /**
     * Build and return the form for editing an object
     *
     * @return Response
     */
    public function getInstanceForm()
    {
        $success = true;
        $instanceId = Input::get('instanceId');
        if (!$instanceId) {
            $success = false;
            $formHtml = 'Error, instance id not found in function parameters';
        } else {
            $instance = Instance::getInstance($instanceId);

            if (!$instance) {
                $success = false;
                $formHtml = "Error, could not find instance for id $instanceId";
            } else {
                $formHtml = '<h1>Comment</h1>

                <label for="title"><b>Title</b></label>
                <input type="hidden" name="instanceId" value="' . $instance->id . '">
                <input type="text" placeholder="Enter Title" name="title" id="title" value="' . $instance->title . '">

                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
            }
        }

        return Response::json(array(
            'success' => $success,
            'formHtml'   => $formHtml
        ));
    }

    /**
     * Show the form for creating a new object
     *
     * @return Response
     */
    public function updateInstance()
    {
        $data = Input::get();

        $formData = [];
        if ($data) {
            foreach ($data as $datum) {
                $formData[$datum['name']] = $datum['value'];
            }
        }

        // Update the instance
        try {
//            Log::info('1', []);
            $instance = Instance::find($formData['instanceId']);
            $instance->title = $formData['title'];
            $instance->save();
        } catch (\Exception $e) {
            Log::info('Error updating data: ' . $formData['instanceId'], [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }

        return Response::json(array(
            'success' => true,
            'data'   => $formData
        ));
    }

    /**
     * Build and return the context menu for an object
     *
     * @return Response
     */
    public function getInstanceContextMenu()
    {
        $success = true;
        $instanceId = Input::get('instanceId');
        if (!$instanceId) {
            $success = false;
            $formHtml = 'Error, instance id not found in function parameters';
        } else {
            $instance = Instance::getInstance($instanceId);
            if (!$instance) {
                $success = false;
                $formHtml = "Error, could not find instance for id $instanceId";
            } else {
                $formHtml = ContextMenu::getContextMenu($instance);
            }
        }

        return Response::json(array(
            'success' => $success,
            'formHtml'   => $formHtml
        ));
    }

    /**
     * Show the form for creating a new object
     *
     * @return Response
     */
    public function create()
    {
        // Show the page
        return view('admin.actionDiagram.create_edit', compact([]));
    }
}
