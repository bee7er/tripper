<?php namespace App\Http\Controllers\Admin;

use App\Http\Helpers\ActionDiagram\FormHelper;
use App\Http\Helpers\ActionDiagram\InstanceHelper;
use App\Model\ContextMenu;
use App\Model\Factories\InstanceFactory;
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
        $messages = [];
        $formHtml = null;
        if (!$instanceId) {
            $success = false;
            $messages[] = 'Error, instance id not found in function parameters';
        } else {
            $instance = InstanceFactory::getInstance($instanceId);

            if (!$instance) {
                $success = false;
                $messages[] = "Error, could not find instance for id $instanceId";
            } else {
                $action = Input::get('action');
                $insertAction = Input::get('insertAction');

                $formHtml = (new FormHelper())->getFormByTypeAndAction($instance, $action, $insertAction);
            }
        }

        return Response::json(array(
            'success' => $success,
            'data'   => ['formHtml' => $formHtml, 'messages' => $messages]
        ));
    }

    /**
     * Update an instance to be pointing at an embedded snippet
     *
     * @return Response
     */
    public function selectedSnippet()
    {
        $formData = $this->getFormData();

        $helper = new InstanceHelper;
        $result = $helper->setSnippet($formData);

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
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

        $messages = [];
        try {
            $instance = Instance::find($params['instanceId']);
            $action = $params['action'];

            switch ($action) {
                case ContextMenu::CM_ACTION_COLLAPSE:
                    // Toggle collapsed status
                    $instance->collapsed = !$instance->collapsed;
                    $instance->save();
                    $messages[] = "'{$instance->title}' " . ($instance->collapsed ? 'collapsed' : 'expanded');
                    break;

                case ContextMenu::CM_ACTION_ZOOM:
                    // Respond with the snippet to zoom
                    return Response::json(array(
                     'success' => true,
                     'data'   => [
                         'action' => ContextMenu::CM_ACTION_ZOOM,
                         'tripId' => $instance->snippetTrip_id,
                         'title' => $instance->title,
                         'messages' => 'Zoomed to snippet ' . $instance->title
                     ]));

                default:
                    return Response::json(array(
                        'success' => false,
                        'data'   => ['messages' => 'Unexpected send action type']
                    ));
            }

        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            Log::info("Error updating instance with id {$params['instanceId']}", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['messages' => $messages]
            ));
        }

        return Response::json(array(
            'success' => true,
            'data'   => ['messages' => $messages]
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
        $formHtml = null;
        $messages = [];
        try {
            $instanceId = Input::get('instanceId');
            if (!$instanceId) {
                $success = false;
                $messages[] = 'Error, instance id not found in function parameters';
            } else {
                $instance = InstanceFactory::getInstance($instanceId);
                if (!$instance) {
                    $success = false;
                    $messages[] = "Error, could not find instance for id $instanceId";
                } else {
                    list($formHtml, $message) = ContextMenu::getContextMenu($instance);
                    $messages[] = $message;
                }
            }

            if (!$success) {
                return [
                    'success' => $success,
                    'data'   => ['messages' => $messages]
                ];
            }
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            Log::info('Error retrieving context menu', [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);

            return Response::json(array(
                'success' => false,
                'data'   => ['messages' => $messages]
            ));
        }

        return Response::json(array(
            'success' => $success,
            'data'   => ['formHtml' => $formHtml, 'messages' => $messages]
        ));
    }
}
