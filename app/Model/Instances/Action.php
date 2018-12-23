<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;
use App\Model\Instance;
use App\Model\Subtype;

class Action extends InstanceBase implements InstanceInterface
{
    /**
     * Action constructor
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        parent::__construct($instance);

        $this->validFields = ['title', 'subtype_id'];
    }

    /**
     * Checks that the instance is not missing anything vital
     *
     * @return bool
     */
    public function isComplete()
    {
        if (($missingActions = $this->getMissingOptions())) {
            // At least one missing action
            return false;
        }

        return true;
    }

    /**
     * Checks what is missing and returns an appropriate ContextMenu option
     * for each additional action that is needed
     *
     * @return bool
     */
    public function getMissingOptions()
    {
        $actions = [];
        if (Subtype::SUBTYPE_SNIPPET === $this->obj->subtype
            && !isset($this->obj->snippetTrip_id)
        ) {
            // A snippet action but no referenced trip
            $actions[] = ContextMenu::CM_ACTION_SELECT_SNIPPET;
        }

        return $actions;
    }
    /**
     * Return the form for editing / inserting an action
     *
     * @param $action
     * @param $insertAction
     * @param $targetInstanceId - the initiator of the event on insert
     * @return string
     */
    public function getEditForm($action, $insertAction, $targetInstanceId = null)
    {
        $select = '<select name="subtype_id" id="subtype_id">';
        $subtypeList = Subtype::getSubtypeList();
        if ($subtypeList) {
            foreach ($subtypeList as $key => $entry) {
                $selected = '';
                if (ContextMenu::CM_ACTION_EDIT === $action) {
                    $selected = ($this->obj->subtype_id == $key ? 'selected': '');
                }
                $select .= "<option $selected value='$key'>" . $entry . "</option>";
            }
        }
        $select .= '</select>';

        return '<h1>'.ucfirst($action).'</h1>
                <input type="hidden" id="instanceId" name="instanceId" value="' . $this->obj->id . '">
                <input type="hidden" id="targetInstanceId" name="targetInstanceId" value="' . $targetInstanceId . '">
                <input type="hidden" id="type" name="type" value="' . $this->obj->type . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
                <label for="title"><strong>Title</strong></label>
                <input type="text" placeholder="Enter title" name="title" id="title" class="focus"
                 			value="' . ($action === ContextMenu::CM_ACTION_INSERT_ACTION ? '' : $this->obj->title) . '">
                <label for="title"><strong>Type</strong></label>
                ' . $select . '
                <hr />
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
    }
}
