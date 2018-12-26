<?php

namespace App\Model\Instances;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Model\Factories\InstanceFactory;
use App\Model\Instance;
use App\Trip;
use Illuminate\Support\Facades\Log;

abstract class InstanceBase implements InstanceInterface
{
    const COLLAPSED_HTML = " - <span class='emphatic'>*collapsed</span>";
    const SELECT_SNIPPET_HTML = " - <span class='emphatic'>*please select a snippet</span>";
    const SELECT_QUESTION_HTML = " - <span class='emphatic'>*please select a question</span>";

    const MAX_LENGTH_LINE = 84;

    /**
     * The instance object
     * @var object
     */
    public $obj;

    /**
     * The instance object
     * @var array
     */
    public $entries;

    /**
     * Valid fields for this object
     * @var array
     */
    public $validFields;

    /**
     * InstanceBase constructor.
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        $this->obj = $instance;
        $this->entries = [];
        $this->validFields = ['title'];
    }

    /**
     * Save an instance, with update or insert
     *
     * @param $formData
     * @return array
     */
    public function save($formData)
    {
        $success = null;
        switch ($formData['action']) {
            case ContextMenu::CM_ACTION_EDIT:
                return $this->update($formData);

            case ContextMenu::CM_ACTION_INSERT_COMMENT:
            case ContextMenu::CM_ACTION_INSERT_CONDITION:
            case ContextMenu::CM_ACTION_INSERT_ELSE:
            case ContextMenu::CM_ACTION_INSERT_ITERATION:
            case ContextMenu::CM_ACTION_INSERT_SEQUENCE:
            case ContextMenu::CM_ACTION_INSERT_ACTION:
                return $this->insert($formData);

            default:
                break;
        }

        return [
            'success' => false,
            'data'   => 'Unknown action',
        ];
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

            if (!$formData['snippetId']) {
                throw new \Exception("Snippet id not supplied");
            }

            $snippetTrip = Trip::find($formData['snippetId']);
            if (!$snippetTrip) {
                throw new \Exception("Snippet instance not found for id {$formData['snippetId']}");
            }

            $this->obj->snippetTrip_id = $formData['snippetId'];
            $this->obj->save();
            $messages[] = "Updated '{$this->obj->title}'";
            $success = true;
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            $success = false;
            Log::info("Error updating instance id {$formData['instanceId']} with snippet id {$formData['snippetId']}", [
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

    /**
     * Update an instance
     *
     * @param $formData
     * @return array
     */
    public function update($formData)
    {
        try {
            $success = false;
            $instance = Instance::find($this->obj->id);
            if (!$instance) {
                throw new \Exception("Update could not find instance for id {$this->obj->id}");
            }

            // Save all parameters by name
            foreach ($formData as $field => $value) {
                // If a valid field then update the instance with each one
                if (!in_array($field, $this->validFields)) {
                    Log::info("Update ignoring invalid form field $field");
                    continue;
                }
                $instance->$field = $value;
            }

            $instance->save();
            $messages[] = "Updated '{$instance->title}'";
            $success = true;
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            Log::info("Error updating instance with id {$formData['instanceId']}", [
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

    /**
     * Insert a new instance
     *
     * @param $formData
     * @return array
     */
    public function insert($formData)
    {
        $success = null;
        $messages = [];
        try {
            $instanceTemplate = Instance::find($formData['instanceId']);
            if (!$instanceTemplate) {
                throw new \Exception("Insert could not find instance template for id {$formData['instanceId']}");
            }

            $sibling = Instance::find($formData['targetInstanceId']);
            if (!$sibling) {
                throw new \Exception("Insert could not find sibling instance for id {$formData['targetInstanceId']}");
            }

            // Where is this instance going?
            switch ($formData['insertAction']) {
                case ContextMenu::INSERT_AFTER:
                    $parentId = $sibling->parent_id;
                    $seq = $sibling->seq + 0.1;
                    $where = 'after';
                    break;
                case ContextMenu::INSERT_BEFORE:
                    $parent = Instance::find($sibling->parent_id);
                    $parentId = $parent->id;
                    $seq = $sibling->seq - 0.1;
                    $where = 'before';
                    break;
                case ContextMenu::INSERT_INSIDE:
                    $parentId = $sibling->id;
                    $seq = 0.1;
                    $where = 'inside';
                    break;
                default:
                    throw new \Exception('Unexpected insert action');
            }

            $instance = new Instance();
            $instance->id = null;
            $instance->trip_id = $sibling->trip_id;
            $instance->block_id = $instanceTemplate->block_id;
            $instance->parent_id = $parentId;
            $instance->seq = $seq;
            $instance->title = $formData['title'];
            if (isset($formData['subtype_id'])) {
                $instance->subtype_id = $formData['subtype_id'];
            }
            $instance->save();
            // NB AFTER Resequence the siblings so we can keep the order
            $this->resequenceInstanceChildren($parentId);
            $messages[] = "Inserted '" . $instance->title . "' $where";
            $success = true;
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            $success = false;
            Log::info('Error inserting data: ' . $formData['title'], [
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

    /**
     * Resequence a set of instances
     *
     * @param $parentId
     * @return void
     */
    private function resequenceInstanceChildren($parentId)
    {
        try {
            $children = Instance::getChildren($parentId);

            if ($children) {
                $seq = 1;
                foreach ($children as $child) {
                    $newChild = Instance::find($child->id);
                    $newChild->seq = $seq++;
                    $newChild->save();
                }
                Log::info('Resequenced children of parent ' . $parentId, []);
            }
        } catch (\Exception $e) {
            Log::info('Error resequencng data: ' . $parentId, [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }
    }

    /**
     * Delete an instance, and all its contents
     *
     * @param $formData
     * @return array
     */
    public function delete($formData)
    {
        try {
            $success = false;
            $instance = Instance::find($this->obj->id);
            if (!$instance) {
                throw new \Exception("Delete could not find instance for id {$this->obj->id}");
            }

            if ($instance->protected) {
                $messages[]  = 'This entry cannot be deleted';
            } else {
                // Ok to delete
                $instance->delete();
                $messages[] = "Deleted instance '{$instance->title}'";
                $success = true;
            }
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            Log::info("Error deleting instance with id {$formData['instanceId']}", [
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

    /**
     * Get the current prefix, comprising the depth and colors of previous levels
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    protected function getPrefix($depth, $colors)
    {
        $prefix = '';
        for ($i=0; $i<($depth - 1); $i++) {
            $prefix .= ("<span style='color: #{$colors[$i]}'>▎</span>");
        }

        return $prefix;
    }

    /**
     * Build and return the string representing the opening line text
     *
     * @return string
     */
    public function getOpeningLineText()
    {
        return ": " . substr($this->obj->title, 0, self::MAX_LENGTH_LINE);
    }

    /**
     * Where do we insert new instances?  It depends.
     *
     * @return array
     */
    public function getInsertAction()
    {
        return ['Insert after', ContextMenu::INSERT_AFTER];
    }

    /**
     * Build and return the string representing the opening line
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    public function getOpeningLine($depth, $colors)
    {
        $prefix = $this->getPrefix($depth, $colors);

        list($title, $insertAction) = $this->getInsertAction();

        return (
            "<div class='row-selected' id='{$this->obj->id}_$insertAction'>"
            . $prefix
            . "<span style='color: #{$this->obj->color}' title='$title'>"
            . $this->obj->top1
            . $this->obj->top2
            . '&nbsp;&nbsp;'
            . $this->obj->type . ($this->obj->subtype ? " {$this->obj->subtype}: " : '')
            . $this->getOpeningLineText()
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the first line of a container
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    public function getContainerLine($depth, $colors)
    {
        $prefix = $this->getPrefix($depth, $colors);

        return (
            "<div class='row-selected' id='{$this->obj->id}_" . ContextMenu::INSERT_INSIDE . "'>"
            . $prefix
            . "<span style='color: #{$this->obj->color}' title='Insert inside'>"
            . $this->obj->side
            . '-&nbsp;&nbsp;'
            . $this->obj->title
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the closing line
     *
     * @param $depth
     * @param $colors
     * @param $blockType - overrides the instance block type
     * @param $title - overrides the instance title
     * @return string
     */
    public function getClosingLine($depth, $colors, $blockType = null, $title = null)
    {
        $prefix = $this->getPrefix($depth, $colors);

        if (null === $blockType) {
            $blockType = $this->obj->type;
        }

        if (null === $title) {
            $title = $this->obj->title;
        }

        return (
            "<div class='row-selected' id='{$this->obj->id}_" . ContextMenu::INSERT_AFTER . "'>"
            . $prefix
            . "<span style='color: #{$this->obj->color}' title='Insert after'>"
            . $this->obj->bottom1
            . $this->obj->bottom2
            . '&nbsp;&nbsp;'
            . $blockType
            . ': End '
            . $title
            . "</span></div>"
        );
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
     * Checks what is missing and returns an appropriate ContextMenu option
     * for each additional action that is needed
     *
     * @return bool
     */
    public function getAdditionalOptions()
    {
        return [];
    }

    /**
     * Returns the title label for the edit form
     *
     * @param $action
     * @return string
     */
    public function getEditFormTitle($action)
    {
        $label = $this->obj->label;
        if (ContextMenu::CM_ACTION_EDIT !== $action) {
            // Insert mode
            $label = '';
        }

        return $label;
    }

    /**
     * Returns the content for the edit form
     *
     * @param $action
     * @return string
     */
    public function getEditFormBody($action)
    {
        return '
            <div class="md-form mb-5">
              <label for="title"><strong>Title</strong></label>
              <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . $this->obj->title . '">
            </div>
        ';
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
        $html .= $this->getEditFormBody($action);


        return $this->getFormWrapper($title, $html);
    }

    /**
     * Return the form for deleting an instance
     *
     * @param $action
     * @return string
     */
    public function getDeleteForm($action)
    {
        $title = ucfirst(str_replace('-', '', $action)) . ' Entry';
        $html = '<input type="hidden" id="instanceId" name="instanceId" value="' . $this->obj->id . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="type" name="type" value="' . $this->obj->type . '">
                <div>Are you sure you want to delete the following entry:</div><br>
                <div><strong>' . $this->obj->title . '</strong></div>';

        return $this->getFormWrapper($title, $html);
    }

    /**
     * Return the form for selecting a snippet
     *
     * @param $action
     * @param $insertAction
     * @return string
     */
    public function getSelectSnippetForm()
    {
        $html = '<input type="hidden" id="instanceId" name="instanceId" value="' . $this->obj->id . '">
                    <input type="hidden" id="type" name="type" value="' . $this->obj->type . '">';
        $html .= '<table width="300px"><thead><th>Id</th><th>Title</th></thead>';
        $html .= '<tbody>';

        $trips = Trip::where('id', '!=', $this->obj->trip_id)->get();

        if (count($trips)) {
            foreach ($trips as $trip) {
                $html .= '<tr id="snippet_' . $trip->id . '" class="snippet">';
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
     * Wraps the content in the various lightbox blocks
     *
     * @param $action
     * @param $insertAction
     * @return string
     */
    public function getFormWrapper($formTitle, $body)
    {
        $html = '<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">' . $formTitle . '</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body mx-3">' .

            $body .

        '</div>
              <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>
              </div>
            </div>
          </div>
        </div>';

        return $html;
    }
}
