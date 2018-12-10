<?php namespace App\Http\Controllers\Admin;

use App\Http\Helpers\ActionDiagram\FormHelper;
use App\Http\Helpers\ActionDiagram\InstanceHelper;
use App\Model\ContextMenu;
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

                $formHtml = (new FormHelper())->getFormByTypeAndAction($instance, $action, $insertAction);
            }
        }

        return Response::json(array(
            'success' => $success,
            'formHtml'   => $formHtml
        ));
    }

    /**
     * Save an instance, with update or insert
     *
     * @return Response
     */
    public function saveInstance()
    {
        $formData = $this->getFormData();

        $helper = new InstanceHelper;
        $result = $helper->save($formData);

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
        ));
    }

    /**
     * Insert an instance
     *
     * @return Response
     */
    public function insertInstance()
    {
        $formData = $this->getFormData();

        $result = (new InstanceHelper)->insert($formData);

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
        ));
    }

    /**
     * Update an instance, with update or insert
     *
     * @return Response
     */
    public function updateInstance()
    {
        $formData = $this->getFormData();

        $result = (new InstanceHelper)->update($formData);

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
        ));
    }

    /**
     * Delete an instance
     *
     * @return Response
     */
    public function deleteInstance()
    {
        $formData = $this->getFormData();

        $result = (new InstanceHelper)->delete($formData);

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
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
            $message = "'{$instance->title}' " . ($instance->collapsed ? 'collapsed' : 'expanded');
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
     * Build and return the context menu for an object
     *
     * @return Response
     */
    public function getInstanceContextMenu()
    {
        $success = true;
        $message = $formHtml = null;
        try {
            $instanceId = Input::get('instanceId');
            if (!$instanceId) {
                $success = false;
                $message = 'Error, instance id not found in function parameters';
            } else {
                $instance = Instance::getInstance($instanceId);
                if (!$instance) {
                    $success = false;
                    $message = "Error, could not find instance for id $instanceId";
                } else {
                    list($formHtml, $message) = ContextMenu::getContextMenu($instance);
                }
            }

            if (!$success) {
                return [
                    'success' => $success,
                    'data'   => ['message' => $message]
                ];
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
            'data'   => ['formHtml' => $formHtml, 'message' => $message]
        ));
    }
}
