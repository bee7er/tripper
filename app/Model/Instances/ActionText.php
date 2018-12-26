<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;
use App\Model\Instance;
use App\Model\Subtype;

class ActionText extends Action
{
    /**
     * Returns the html content for the action
     *
     * @param $action
     * @return string
     */
    public function getTitleHtml($action)
    {
        return '<label for="title"><strong>Title</strong></label>:&nbsp;
            <textarea placeholder="Enter action text" name="title" id="title" class="focus" cols="60" rows="5">' .
                ($action === ContextMenu::CM_ACTION_INSERT_ACTION ? '' : $this->obj->title) .
            '</textarea>';
    }
}
