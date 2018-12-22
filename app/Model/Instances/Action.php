<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;
use App\Model\Subtype;

class Action extends InstanceBase implements InstanceInterface
{
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
        if (Subtype::SUBTYPE_SNIPPET === $this->instance->subtype
            && !isset($this->instance->snippetTrip_id)
        ) {
            // A snippet action but no referenced trip
            $actions[] = ContextMenu::CM_ACTION_SELECT_SNIPPET;
        }

        return $actions;
    }
}
