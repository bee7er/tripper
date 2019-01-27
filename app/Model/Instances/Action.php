<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;
use App\Model\Instance;
use App\Model\Subtype;

class Action extends InstanceBase
{
    /**
     * Action constructor
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        parent::__construct($instance);

        $this->validFields[] = 'subtype_id';
    }

    /**
     * Build and return the string representing the opening line text
     *
     * @return string
     */
    public function getOpeningLineText()
    {
        return ": " . trunc($this->obj->title, self::MAX_LENGTH_LINE);
    }

    /**
     * Checks that the instance is not missing anything vital
     *
     * @return bool
     */
    public function isComplete()
    {
        return true;
    }

    /**
     * Checks that the instance is zoomable
     *
     * @return bool
     */
    public function isZoomable()
    {
        return $this->obj->subtype === Subtype::SUBTYPE_SNIPPET;
    }

    /**
     * Checks what is missing and returns an appropriate ContextMenu option
     * for each additional action that is needed
     *
     * @return array
     */
    public function getAdditionalOptions()
    {
        return [];
    }

    /**
     * In edit mode we can allow more fields to be edited, according to type
     *
     * @return string
     */
    public function getAdditionalEditForm()
    {
        return '';
    }

    /**
     * Returns the title label for the edit form
     *
     * @param $action
     * @return string
     */
    public function getEditFormTitle($action)
    {
        return (ContextMenu::CM_ACTION_EDIT === $action ? 'action' : '');
    }

    /**
     * Returns the content for the edit form
     *
     * @param $action
     * @return string
     */
    public function getEditFormBody($action)
    {
        $additionalEditForm = $disabled = '';
        if (ContextMenu::CM_ACTION_EDIT === $action) {
            $additionalEditForm = $this->getAdditionalEditForm();
            $disabled = "disabled='disabled'";
        }

        $select = '<select name="subtype_id" id="subtype_id" ' . $disabled . '>';
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

        return '
            <div class="md-form mb-5">' .

                $this->getTitleHtml($action) .

                '<br>
                <label for="type"><strong>Type</strong></label>:&nbsp;
                ' . $select . '
                ' . $additionalEditForm . '
                <hr />
            </div>
        ';
    }

    /**
     * Returns the html content for the action
     *
     * @param $action
     * @return string
     */
    public function getTitleHtml($action)
    {
        return '<label for="title"><strong>Title</strong></label>:&nbsp;
        <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' .
            ($action === ContextMenu::CM_ACTION_INSERT_ACTION ? '' : $this->obj->title) .
        '">';
    }
}
