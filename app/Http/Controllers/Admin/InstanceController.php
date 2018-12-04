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
                        return $this->getCommentForm($instance, $action);

                    case Block::BLOCK_TYPE_ACTION:

                    default:
                        break;
                }
                break;

            case ContextMenu::CM_ACTION_DELETE:
                return $this->getDeleteForm($instance, $action);

            case ContextMenu::CM_ACTION_INSERT_COMMENT:
                return $this->getCommentForm($instance, $action);

            default:
                break;
        }

        return false;
    }

    /**
     * Return the form for deleting an instance
     *
     * @param null $instance
     * @param $action
     * @return string
     */
    public function getDeleteForm($instance, $action)
    {
        return '<h1>'.ucfirst($action).' Entry</h1>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <div>Are you sure you want to delete the following entry:</div><br>
                <div><strong>' . $instance->title . '</strong></div><br>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
    }

    /**
     * Return the form for editing / inserting a comment
     *
     * @param null $instance
     * @param $action
     * @return string
     */
    public function getCommentForm($instance, $action)
    {
        return '<h1>'.ucfirst($action).' Comment</h1>
                <label for="title"><b>Title</b></label>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="text" placeholder="Enter Title" name="title" id="title" value="' . ($action === ContextMenu::CM_ACTION_INSERT_COMMENT ? '' : $instance->title) . '">
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
    }

    /**
     * Save an instance, with update or insert
     *
     * @return Response
     */
    public function saveInstance()
    {
        $formData = $this->getFormData();

        switch ($formData['action']) {
            case ContextMenu::CM_ACTION_EDIT:
                return $this->updateInstance($formData);

            case ContextMenu::CM_ACTION_INSERT_COMMENT:
                return $this->insertInstance($formData);

            default:
                break;
        }

        return Response::json(array(
            'success' => true,
            'data'   => 'Unknown action',
        ));
    }

    /**
     * Delete an instance, and all its contents
     *
     * @return Response
     */
    public function deleteInstance()
    {
        $formData = $this->getFormData();

        try {
            $instance = Instance::find($formData['instanceId']);
            $instance->delete();
            Log::info("Delete instance with id {$instance->id}", []);
        } catch (\Exception $e) {
            Log::info("Error deleting instance with id {$instance->id}", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }

        //TODO Completion messages
        return Response::json(array(
            'success' => true,
            'data'   => 'Completion message',
        ));
    }

    /**
     * Update an instance
     *
     * @param $formData
     * @return Response
     */
    public function updateInstance($formData)
    {
        // Update the instance
        try {
            $instance = Instance::find($formData['instanceId']);
            $instance->title = $formData['title'];
            $instance->save();
            Log::info("Update instance with id {$instance->id}", []);
        } catch (\Exception $e) {
            Log::info("Error updating instance with id {$instance->id}", [
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
     * Insert a new instance
     *
     * @param $formData
     * @return Response
     */
    public function insertInstance($formData)
    {
        // Insert the instance
        try {
            $sibling = Instance::find($formData['instanceId']);
            $block = Block::where('type','=',Block::BLOCK_TYPE_COMMENT)->first();

            // NB Inserting AFTER
            if (ContextMenu::CM_ACTION_INSERT_COMMENT === $formData['action']) {
                $parentId = $sibling->parent_id;
            }

            $newInstance = new Instance();
            $newInstance->id = null;
            $newInstance->trip_id = $sibling->trip_id;
            $newInstance->parent_id = $parentId;
            $newInstance->seq = $sibling->seq + 0.1;
            $newInstance->block_id = $block->id;
            $newInstance->title = $formData['title'];
            $newInstance->save();
            // NB AFTER Resequence the siblings so we can keep the order
            $this->resequenceInstanceChildren($parentId);

            Log::info('Insert instance', []);
        } catch (\Exception $e) {
            Log::info('Error inserting data: ' . $formData['title'], [
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
     * Resequence a set of instances
     *
     * @param $parentId
     * @return mixed
     */
    public function resequenceInstanceChildren($parentId)
    {
        try {
            $children = Instance::getChildren($parentId);

            if ($children) {
                $seq = 1;
                foreach ($children as $child) {
                    $newChild = Instance::find($child->id);
                    $newChild->seq = $seq++;
                    $newChild->save();
                }
                Log::info('Resequenced children of parent ' . $parentId, []);
            }
        } catch (\Exception $e) {
            Log::info('Error resequencng data: ' . $parentId, [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }
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
