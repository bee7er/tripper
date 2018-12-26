<?php

namespace App\Model\Instances;

use App\Model\ContextMenu;
use App\Trip;

class ActionSnippet extends Action
{

    /**
     * Build and return the string representing the opening line text
     *
     * @return string
     */
    public function getOpeningLineText()
    {
        $reffedSnippet = '';
        if ($this->obj->snippetTrip_id) {
            $snippetTrip = Trip::find($this->obj->snippetTrip_id);
            if (!$snippetTrip) {
                throw new \Exception("Snippet instance not found for id {$this->obj->snippetTrip_id}");
            }

            $reffedSnippet = ' > ' . $snippetTrip->title;
        }

        return ": " . substr($this->obj->title . $reffedSnippet, 0, self::MAX_LENGTH_LINE);
    }

    /**
     * Checks that the instance is not missing anything vital
     *
     * @return bool
     */
    public function isComplete()
    {
        return ($this->obj->snippetTrip_id > 0);
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
            ContextMenu::CM_ACTION_SELECT_SNIPPET
        ];
    }
}
