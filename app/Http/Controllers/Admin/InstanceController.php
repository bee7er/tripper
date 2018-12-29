<?php namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use App\Model\ContextMenu;
use App\Model\Factories\InstanceFactory;
use App\Model\Instance;
use App\Http\Controllers\AdminController;

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
        $action = Input::get('action');
        $messages = [];
        $formHtml = null;
        if (!$instanceId) {
            $success = false;
            $messages[] = 'Error, instance id not found in function parameters';
        } else {
            $instance = InstanceFactory::getInstance($instanceId, $action);

            if (!$instance) {
                $success = false;
                $messages[] = "Error, could not find instance for id $instanceId";
            } else {
                $insertAction = Input::get('insertAction');

                $formHtml = '';
                switch ($action) {
                    case ContextMenu::CM_ACTION_EDIT:
                        $formHtml = $instance->getEditForm($action, $insertAction);
                        break;

                    case ContextMenu::CM_ACTION_INSERT_ACTION:
                    case ContextMenu::CM_ACTION_INSERT_COMMENT:
                    case ContextMenu::CM_ACTION_INSERT_CONDITION:
                    case ContextMenu::CM_ACTION_INSERT_ELSE:
                    case ContextMenu::CM_ACTION_INSERT_ITERATION:
                    case ContextMenu::CM_ACTION_INSERT_SEQUENCE:
                        // For new instances we use a template object
                        $instanceTemplate = InstanceFactory::getInstanceTemplate($action);
                        if (!$instanceTemplate) {
                            throw new \Exception("Could not find instance template for action $action");
                        }

                        $formHtml = $instanceTemplate->getEditForm($action, $insertAction, $instance->obj->id);
                        break;

                    case ContextMenu::CM_ACTION_DELETE:
                        $formHtml = $instance->getDeleteForm($action);
                        break;

                    case ContextMenu::CM_ACTION_SELECT_QUESTION:
                        $formHtml = $instance->getSelectForm();
                        break;

                    case ContextMenu::CM_ACTION_SELECT_SNIPPET:
                        $formHtml = $instance->getSelectForm();
                        break;

                    default:
                        break;
                }

            }
        }

        return Response::json(array(
            'success' => $success,
            'data'   => ['formHtml' => $formHtml, 'messages' => $messages]
        ));
    }

    /**
     * Update an instance to be pointing at an embedded question
     *
     * @return Response
     */
    public function selectedQuestion()
    {
        $result = [];
        try {
            $formData = $this->getFormData();

            if (!isset($formData['instanceId'])) {
                throw new \Exception('Instance id not supplied for set question function');
            }

            $instance = InstanceFactory::getInstance($formData['instanceId']);
            if (!$instance) {
                throw new \Exception("Error, could not find instance for id {$formData['instanceId']}");
            }

            if (!is_a($instance, 'App\Model\Instances\ActionQuestion')) {
                throw new \Exception("Error, instance is the wrong type for a question reference");
            }

            $result = $instance->setQuestion($formData);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['data'] = ['messages' => [$e->getMessage() . ' For more info see log.']];

            Log::info("Error setting snippet", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                print_r($formData, true)
            ]);
        }

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
        ));
    }

    /**
     * Update an instance to be pointing at an embedded snippet
     *
     * @return Response
     */
    public function selectedSnippet()
    {
        $result = [];
        try {
            $formData = $this->getFormData();

            if (!isset($formData['instanceId'])) {
                throw new \Exception('Instance id not supplied for set snippet function');
            }

            $instance = InstanceFactory::getInstance($formData['instanceId']);
            if (!$instance) {
                throw new \Exception("Error, could not find instance for id {$formData['instanceId']}");
            }

            if (!is_a($instance, 'App\Model\Instances\ActionSnippet')) {
                throw new \Exception("Error, instance is the wrong type for a snippet reference");
            }

            $result = $instance->setSnippet($formData);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['data'] = ['messages' => [$e->getMessage() . ' For more info see log.']];

            Log::info("Error setting snippet", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                print_r($formData, true)
            ]);
        }

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
        $result = [];
        try {
            $formData = $this->getFormData();

            if (!isset($formData['instanceId'])) {
                throw new \Exception('Instance id not supplied for save function');
            }

            $instance = InstanceFactory::getInstance($formData['instanceId']);
            if (!$instance) {
                throw new \Exception("Error, could not find instance for id {$formData['instanceId']}");
            }

            $result = $instance->save($formData);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['data'] = ['messages' => [$e->getMessage() . ' For more info see log.']];

            Log::info("Error saving instance", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                print_r($formData, true)
            ]);
        }

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
        die('should not be used insert');
//        $formData = $this->getFormData();
//
//        $result = (new InstanceHelper)->insert($formData);
//
//        return Response::json(array(
//            'success' => $result['success'],
//            'data'   => $result['data'],
//        ));
    }

    /**
     * Update an instance, with update or insert
     *
     * @return Response
     */
    public function updateInstance()
    {
        die('should not be used update');
//        $formData = $this->getFormData();
//
//        $result = (new InstanceHelper)->update($formData);
//
//        return Response::json(array(
//            'success' => $result['success'],
//            'data'   => $result['data'],
//        ));
    }

    /**
     * Delete an instance
     *
     * @return Response
     */
    public function deleteInstance()
    {
        $result = [];
        try {
            $formData = $this->getFormData();

            if (!isset($formData['instanceId'])) {
                throw new \Exception('Instance id not supplied for save function');
            }

            $instance = InstanceFactory::getInstance($formData['instanceId']);

            if (!$instance) {
                throw new \Exception("Error, could not find instance for id {$formData['instanceId']}");
            }

            $result = $instance->delete($formData);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['data'] = ['messages' => [$e->getMessage() . ' For more info see log.']];

            Log::info("Error saving instance", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                print_f($formData, true)
            ]);
        }

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
            $instanceIdDtls = Input::get('instanceId');

            if (!$instanceIdDtls) {
                $success = false;
                $messages[] = 'Error, instance id not found in function parameters';
            } else {
                $instanceId = explode('_', $instanceIdDtls)[0];
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
