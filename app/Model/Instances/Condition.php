<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;

class Condition extends InstanceBase
{
    /**
     * Build and return the string representing the opening line text
     *
     * @return string
     */
    public function getOpeningLineText()
    {
        return $this->obj->collapsed ? self::COLLAPSED_HTML: '';
    }

    /**
     * Where do we insert new instances?  It depends.
     *
     * @return string
     */
    public function getInsertAction()
    {
        return ['Insert before', ContextMenu::INSERT_BEFORE];
    }

    /**
     * Return the form for editing / inserting an instance
     * This base class funciton allows just the title only to be maintained
     *
     * @param $action
     * @param $insertAction
     * @param $targetInstanceId - the initiator of the event on insert
     * @return string
     */
    public function getEditForm($action, $insertAction, $targetInstanceId = null)
    {

        $title =  ucwords(str_replace('-', ' ', $action) . ' ' . $this->getEditFormTitle($action));
        $html = '<input type="hidden" id="instanceId" name="instanceId" value="' . $this->obj->id . '">
                <input type="hidden" id="targetInstanceId" name="targetInstanceId" value="' . $targetInstanceId . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
                <input type="hidden" id="type" name="type" value="' . $this->obj->type . '">';

        if (ContextMenu::INSERT_INSIDE === $insertAction) {
            $html .= $this->getEditCondition($action);
        } else {
            $html .= $this->getEditFormBody($action);
        }

        return $this->getFormWrapper($title, $html);
    }

    /**
     * Returns the content for the edit form
     *
     * @param $action
     * @return string
     */
    public function getEditCondition($action)
    {
        return '
            <div class="md-form mb-5">
              <label for="title"><strong>Title</strong></label>
              <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . $this->obj->title . '">
              <div>Other bits and pieces</div>
            </div>
        ';
    }

}
