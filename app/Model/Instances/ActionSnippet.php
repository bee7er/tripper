<?php

namespace App\Model\Instances;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Model\Instance;
use App\Trip;
use Illuminate\Support\Facades\Log;

class ActionSnippet extends Action
{
    /**
     * Action constructor
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        parent::__construct($instance);

        $this->validFields[] = 'snippetTrip_id';
    }

    /**
     * Build and return the string representing the opening line text
     *
     * @return string
     * @throws \Exception
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
     * Return any notifications in the opening line text
     *
     * @return string
     */
    public function getOpeningLineNotices()
    {
        return $this->isComplete() ? '' : ": " . self::SELECT_SNIPPET_HTML;
    }

    /**
     * Checks that the instance is not missing anything vital
     *
     * @return bool
     */
    public function isComplete()
    {
        return (null !== $this->obj->snippetTrip_id && $this->obj->snippetTrip_id > 0);
    }

    /**
     * Returns an appropriate ContextMenu option for each additional action that is supported
     *
     * @return array
     */
    public function getAdditionalOptions()
    {
        return [
            ContextMenu::CM_ACTION_SELECT_SNIPPET
        ];
    }

    /**
     * In edit mode we can allow more fields to be edited, according to type
     *
     * @return string
     */
    public function getAdditionalEditForm()
    {
        // Allow the user to select a snippet
        $trips = Trip::get();

        $select = '<br><label for="snippetTrip_id"><strong>Snippet</strong></label>:&nbsp;';
        if (count($trips)) {
            $select .= '<select name="snippetTrip_id" id="snippetTrip_id">';
            foreach ($trips as $entry) {
                if ($this->obj->trip_id === $entry->id) {
                    continue;
                }
                $selected = ($this->obj->snippetTrip_id === $entry->id ? 'selected': '');
                $select .= "<option $selected value='$entry->id'>" . $entry->title . "</option>";
            }
            $select .= '</select>';
        }

        return "<div>$select</div>";
    }

    /**
     * Return the form for selecting an instance
     *
     * @param $action
     * @param $insertAction
     * @return string
     */
    public function getSelectForm()
    {
        $html = '<input type="hidden" id="instanceId" name="instanceId" value="' . $this->obj->id . '">
                    <input type="hidden" id="type" name="type" value="' . $this->obj->type . '">';
        $html .= '<table width="300px"><thead><th>Id</th><th>Title</th></thead>';
        $html .= '<tbody>';

        $trips = Trip::where('id', '!=', $this->obj->trip_id ? : 0)->get();

        if (count($trips)) {
            foreach ($trips as $trip) {
                $html .= '<tr id="snippetTrip_' . $trip->id . '" class="snippet">';
                $html .= '<td>' . $trip->id . '</td>';
                $html .= '<td>' . $trip->title . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">No snippets found</td></tr>';
        }
        $html .= '</tbody></table>';

        return $this->getFormWrapper('Select Snippet', $html);
    }

    /**
     * Update an instance to point at a snippet
     *
     * @param $formData
     * @return array
     */
    public function setSnippet($formData)
    {
        $success = null;
        $messages = [];
        try {
            if (Block::BLOCK_TYPE_ACTION !== $this->obj->type) {
                throw new \Exception("Type {$this->obj->type} not supported for this function");
            }

            if (!$formData['snippetTrip_id']) {
                throw new \Exception("Snippet id not supplied");
            }

            $snippetTrip = Trip::find($formData['snippetTrip_id']);
            if (!$snippetTrip) {
                throw new \Exception("Snippet instance not found for id {$formData['snippetTrip_id']}");
            }

            $this->obj->snippetTrip_id = $formData['snippetTrip_id'];
            $this->obj->save();
            $messages[] = "Updated '{$this->obj->title}'";
            $success = true;
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            $success = false;
            Log::info("Error updating instance id {$formData['instanceId']} with snippet id {$formData['snippetTrip_id']}", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }

        return [
            'success' => $success,
            'data'   => ['messages' => $messages]
        ];
    }
}
