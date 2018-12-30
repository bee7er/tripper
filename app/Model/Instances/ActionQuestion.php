<?php

namespace App\Model\Instances;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Model\Instance;
use App\Model\Question;
use Illuminate\Support\Facades\Log;

class ActionQuestion extends Action
{
    /**
     * Action constructor
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        parent::__construct($instance);

        $this->validFields[] = 'question_id';
    }

    /**
     * Build and return the string representing the opening line text
     *
     * @return string
     * @throws \Exception
     */
    public function getOpeningLineText()
    {
        $reffedQuestion = '';
        if ($this->obj->question_id) {
            $question = Question::find($this->obj->question_id);
            if (!$question) {
                throw new \Exception("Question instance not found for id {$this->obj->question_id}");
            }

            $reffedQuestion = ' > ' . $question->label;
        }

        return ": " . substr($this->obj->title . $reffedQuestion, 0, self::MAX_LENGTH_LINE);
    }
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
        return (null !== $this->obj->question_id && $this->obj->question_id > 0);
    }

    /**
     * Returns an appropriate ContextMenu option for each additional action that is supported
     *
     * @return array
     */
    public function getAdditionalOptions()
    {
        return [
            ContextMenu::CM_ACTION_SELECT_QUESTION
        ];
    }

    /**
     * In edit mode we can allow more fields to be edited, according to type
     *
     * @return string
     */
    public function getAdditionalEditForm()
    {
        // Allow the user to select a question
        $questions = Question::get();

        $select = '<br><label for="question_id"><strong>Question</strong></label>:&nbsp;';
        if (count($questions)) {
            $select .= '<select name="question_id" id="question_id">';
            foreach ($questions as $entry) {
                $selected = ($this->obj->question_id == $entry->id ? 'selected': '');
                $select .= "<option $selected value='$entry->id'>" . $entry->label . "</option>";
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

        $questions = Question::where('id', '!=', $this->obj->question_id ? : 0)->get();

        if (count($questions)) {
            foreach ($questions as $question) {
                $html .= '<tr id="question_' . $question->id . '" class="question">';
                $html .= '<td>' . $question->id . '</td>';
                $html .= '<td>' . $question->label . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">No questions found</td></tr>';
        }
        $html .= '</tbody></table>';

        return $this->getFormWrapper('Select Question', $html);
    }

    /**
     * Update an instance to point at a question
     *
     * @param $formData
     * @return array
     */
    public function setQuestion($formData)
    {
        $success = null;
        $messages = [];
        try {
            if (Block::BLOCK_TYPE_ACTION !== $this->obj->type) {
                throw new \Exception("Type {$this->obj->type} not supported for this function");
            }

            if (!$formData['questionId']) {
                throw new \Exception("Question id not supplied");
            }

            $question = Question::find($formData['questionId']);
            if (!$question) {
                throw new \Exception("Question instance not found for id {$formData['snippetId']}");
            }

            $this->obj->question_id = $formData['questionId'];
            $this->obj->save();
            $messages[] = "Updated '{$this->obj->title}'";
            $success = true;
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            $success = false;
            Log::info("Error updating instance id {$formData['instanceId']} with question id {$formData['questionId']}", [
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
