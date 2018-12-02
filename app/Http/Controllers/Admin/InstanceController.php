<?php namespace App\Http\Controllers\Admin;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;
use App\Model\Instance;
use Illuminate\Support\Facades\Response;

class InstanceController extends AdminController
{
    /**
     * ActionDiagramController constructor.
     */
    public function __construct()
    {
        view()->share('type', 'instance');
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
                $action = Input::get('action');

                $formHtml = $this->getFormByTypeAndAction($instance, $action);
            }
        }

        return Response::json(array(
            'success' => $success,
            'formHtml'   => $formHtml
        ));
    }

    /**
     * Return the form for editing or creating a new instance
     *
     * @param $instance
     * @param $action
     * @return bool|string
     */
    public function getFormByTypeAndAction($instance, $action)
    {
        switch ($action) {
            case ContextMenu::CM_ACTION_EDIT:
                switch ($instance->type) {
                    case Block::BLOCK_TYPE_COMMENT:
                        return $this->getCommentForm($action, $instance);

                    case Block::BLOCK_TYPE_ACTION:

                    default:
                        break;
                }
                break;

            case ContextMenu::CM_ACTION_INSERT_COMMENT:
                return $this->getCommentForm($action);

            default:
                break;
        }

        return false;
    }



    /**
     * Return the form for editing / inserting a comment
     *
     * @param $action
     * @param null $instance
     * @return string
     */
    public function getCommentForm($action, $instance = null)
    {
        return '<h1>'.ucfirst($action).' Comment</h1>
                <label for="title"><b>Title</b></label>
                <input type="hidden" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" name="action" value="' . $action . '">
                <input type="text" placeholder="Enter Title" name="title" id="title" value="' . ($instance ? $instance->title : '') . '">
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
    }

    /**
     * Update an instance
     *
     * @return Response
     */
    public function updateInstance()
    {
        $formData = $this->getFormData();

        // Update the instance
        try {
            $instance = Instance::find($formData['instanceId']);
            $instance->title = $formData['title'];
            $instance->save();
            Log::info('Update instance', []);
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
}
