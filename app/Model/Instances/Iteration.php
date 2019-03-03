<?php

namespace App\Model\Instances;

use App\Model\Context;
use App\Model\ContextMenu;
use App\Model\Operator;
use App\Model\Response;

class Iteration extends AbstractInstance
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
     * @return array
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
            $html .= $this->getEditIteration($action);
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
    public function getEditIteration($action)
    {
        $html = '<div class="md-form mb-5">';
        $html .= '<label for="title"><strong>Title</strong></label>';
        $html .= '<input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . $this->obj->title . '">';
        $html .= '<div>';
        $html .= 'while&nbsp;&nbsp;';
        $html .= 'RSP&nbsp;<strong>.</strong>&nbsp;';
        $html .= Response::getResponsesList();
        $html .= '&nbsp;&nbsp;';
        $html .= Operator::getOperatorsList();
        $html .= '&nbsp;&nbsp;';
        $html .= Context::getContextsList();
        $html .= '&nbsp;<strong>.</strong>&nbsp;';
        $html .= Response::getResponsesList();
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
