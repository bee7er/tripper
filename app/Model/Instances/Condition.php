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
}
