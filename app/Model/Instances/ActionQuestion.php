<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;
use App\Model\Instance;
use App\Model\Subtype;

class ActionQuestion extends Action
{
    /**
     * Return any notifications in the opening line text
     *
     * @return string
     */
    public function getOpeningLineNotices()
    {
        return $this->isComplete() ? '' : ": " . self::SELECT_QUESTION_HTML;
    }

    /**
     * Checks that the instance is not missing anything vital
     *
     * @return bool
     */
    public function isComplete()
    {
        return ($this->obj->question_id > 0);
    }

    /**
     * Checks what is missing and returns an appropriate ContextMenu option
     * for each additional action that is needed
     *
     * @return bool
     */
    public function getAdditionalOptions()
    {
        return [
            ContextMenu::CM_ACTION_SELECT_QUESTION
        ];
    }
}
