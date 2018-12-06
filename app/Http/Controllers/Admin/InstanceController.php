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
                $insertAction = Input::get('insertAction');

                $formHtml = $this->getFormByTypeAndAction($instance, $action, $insertAction);
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
     * @param $insertAction
     * @return bool|string
     */
    public function getFormByTypeAndAction($instance, $action, $insertAction)
    {
        switch ($action) {
            case ContextMenu::CM_ACTION_EDIT:
                switch ($instance->type) {
                    case Block::BLOCK_TYPE_COMMENT:
                        return $this->getCommentForm($instance, $action, $insertAction);

                    case Block::BLOCK_TYPE_ACTION:

                    default:
                        break;
                }
                break;

            case ContextMenu::CM_ACTION_DELETE:
                return $this->getDeleteForm($instance, $action);

            case ContextMenu::CM_ACTION_INSERT_COMMENT:
                return $this->getCommentForm($instance, $action, $insertAction);

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
     * @param $instance
     * @param $action
     * @param $insertAction
     * @return string
     */
    public function getCommentForm($instance, $action, $insertAction)
    {
        return '<h1>'.ucfirst($action).' Comment</h1>
                <label for="title"><b>Title</b></label>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
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

        $message = null;
        try {
            $instance = Instance::find($formData['instanceId']);
            $instance->delete();
            $message = "Delete instance with id {$instance->id}";
            Log::info($message, []);
        } catch (\Exception $e) {
            $message  = $e->getMessage() . ' For more info see log.';
            Log::info("Error deleting instance with id {$formData['instanceId']}", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['message' => $message]
            ));
        }

        return Response::json(array(
            'success' => true,
            'data'   => ['message' => $message]
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
        $message = null;
        try {
            $instance = Instance::find($formData['instanceId']);
            $instance->title = $formData['title'];
            $instance->save();
            $message = "Update instance with id {$instance->id}";
            Log::info($message, []);
        } catch (\Exception $e) {
            $message  = $e->getMessage() . ' For more info see log.';
            Log::info("Error updating instance with id {$formData['instanceId']}", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['message' => $message]
            ));
        }

        return Response::json(array(
            'success' => true,
            'data'   => ['message' => $message]
        ));
    }

    /**
     * Process the sending of an action
     *
     * @param $formData
     * @return Response
     */
    public function sendAction()
    {
        $params = Input::get();

        $message = null;
        try {
            $instance = Instance::find($params['instanceId']);
            $action = $params['action'];

            if (ContextMenu::CM_ACTION_COLLAPSE === $action) {
                // Toggle collapsed status
                $instance->collapsed = !$instance->collapsed;
            }

            $instance->save();
            $message = "Instance with id {$instance->id} " . ($instance->collapsed ? 'collapsed' : 'expanded');
            Log::info($message, []);
        } catch (\Exception $e) {
            $message  = $e->getMessage() . ' For more info see log.';
            Log::info("Error updating instance with id {$params['instanceId']}", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['message' => $message]
            ));
        }

        return Response::json(array(
            'success' => true,
            'data'   => ['message' => $message]
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
        $message = null;
        try {
            $sibling = Instance::find($formData['instanceId']);
            $block = Block::where('type','=',Block::BLOCK_TYPE_COMMENT)->first();

            // Where is this instance going?
            $parentId = $seq = null;
            switch ($formData['insertAction']) {
                case ContextMenu::INSERT_AFTER:
                    $parentId = $sibling->parent_id;
                    $seq = $sibling->seq + 0.1;
                    break;
                case ContextMenu::INSERT_BEFORE:
                    $parent = Instance::find($sibling->parent_id);
                    $parentId = $parent->id;
                    $seq = $sibling->seq - 0.1;
                    break;
                case ContextMenu::INSERT_INSIDE:
                    $parentId = $sibling->id;
                    $seq = 0.1;
                    break;
                default:
                    throw new \Exception('Unexpected insert action');
            }

            $newInstance = new Instance();
            $newInstance->id = null;
            $newInstance->trip_id = $sibling->trip_id;
            $newInstance->parent_id = $parentId;
            $newInstance->seq = $seq;
            $newInstance->block_id = $block->id;
            $newInstance->title = $formData['title'];
            $newInstance->save();
            // NB AFTER Resequence the siblings so we can keep the order
            $this->resequenceInstanceChildren($parentId);

            $message = 'Insert instance ' . $newInstance->id;
            Log::info($message, []);
        } catch (\Exception $e) {
            $message  = $e->getMessage() . ' For more info see log.';
            Log::info('Error inserting data: ' . $formData['title'], [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['message' => $message]
            ));
        }

        return Response::json(array(
            'success' => true,
            'data'   => ['message' => $message]
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
        try {
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
        } catch (\Exception $e) {
            $message  = $e->getMessage() . ' For more info see log.';
            Log::info('Error retrieving context menu', [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['message' => $message]
            ));
        }

        return Response::json(array(
            'success' => $success,
            'formHtml'   => $formHtml
        ));
    }
}
