<?php

namespace App\Http\Helpers\ActionDiagram;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Model\Instance;
use App\Model\Instances\InstanceInterface;
use App\Model\Subtype;
use App\Trip;
use Illuminate\Support\Facades\Log;

class FormHelper
{
	/**
	 * Return the form for editing or creating a new instance
	 *
	 * @param $instance
	 * @param $action
	 * @param $insertAction
	 * @return bool|string
	 */
	public function getFormByTypeAndAction(InstanceInterface $instance, $action, $insertAction)
	{
		switch ($action) {
			case ContextMenu::CM_ACTION_EDIT:
				switch ($instance->obj->type) {
					case Block::BLOCK_TYPE_ACTION:
						return $this->getActionForm($instance, $action, $insertAction);

					case Block::BLOCK_TYPE_COMMENT:
					case Block::BLOCK_TYPE_CONDITION:
					case Block::BLOCK_TYPE_ITERATION:
					case Block::BLOCK_TYPE_SEQUENCE:
						return $this->getTitleForm($instance, $action, $insertAction);

					default:
						break;
				}
				break;

			case ContextMenu::CM_ACTION_DELETE:
				return $this->getDeleteForm($instance, $action);

			case ContextMenu::CM_ACTION_INSERT_ACTION:
				return $this->getActionForm($instance, $action, $insertAction);

			case ContextMenu::CM_ACTION_INSERT_COMMENT:
			case ContextMenu::CM_ACTION_INSERT_CONDITION:
			case ContextMenu::CM_ACTION_INSERT_ITERATION:
			case ContextMenu::CM_ACTION_INSERT_SEQUENCE:
				return $this->getTitleForm($instance, $action, $insertAction);

			case ContextMenu::CM_ACTION_SELECT_SNIPPET:
				return $this->getSelectSnippetForm($instance);

			default:
				break;
		}

		return false;
	}

	/**
	 * Return the form for selecting a snippet
	 *
	 * @param $instance
	 * @param $action
	 * @param $insertAction
	 * @return string
	 */
	private function getSelectSnippetForm(InstanceInterface $instance)
	{
		$trips = Trip::where('id', '!=', $instance->obj->trip_id)->get();

		$html = '<h2>Select Snippet</h2>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->obj->id : '') . '">
                <input type="hidden" id="type" name="type" value="' . $instance->obj->type . '">';

		$html .= '<table width="300px"><thead><th>Id</th><th>Title</th></thead>';
		$html .= '<tbody>';
		if ($trips) {
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

		$html .= '<hr />
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>';

		return $html;
	}

	/**
	 * Return the form for editing / inserting an action
	 *
	 * @param $instance
	 * @param $action
	 * @param $insertAction
	 * @return string
	 */
	private function getActionForm(InstanceInterface $instance, $action, $insertAction)
	{
		$select = '<select name="subtype_id" id="subtype_id">';
		$subtypeList = Subtype::getSubtypeList();
		if ($subtypeList) {
			foreach ($subtypeList as $key => $entry) {
				$selected = '';
				if (ContextMenu::CM_ACTION_EDIT === $action) {
					$selected = ($instance->obj->subtype_id == $key ? 'selected': '');
				}
				$select .= "<option $selected value='$key'>" . $entry . "</option>";
			}
		}
		$select .= '</select>';

		return '<h1>'.ucfirst($action).' Action</h1>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->obj->id : '') . '">
                <input type="hidden" id="type" name="type" value="' . $instance->obj->type . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
                <label for="title"><strong>Title</strong></label>
                <input type="text" placeholder="Enter title" name="title" id="title" class="focus"
                 			value="' . ($action === ContextMenu::CM_ACTION_INSERT_ACTION ? '' : $instance->obj->title) . '">
                <label for="title"><strong>Type</strong></label>
                ' . $select . '
                <hr />
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
	}

	/**
	 * Return the form for deleting an instance
	 *
	 * @param InstanceInterface $instance
	 * @param $action
	 * @return string
	 */
	private function getDeleteForm(InstanceInterface $instance, $action)
	{
		return '<h1>' . ucfirst(str_replace('-', '', $action)) . ' Entry</h1>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->obj->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="type" name="type" value="' . $instance->obj->type . '">
                <div>Are you sure you want to delete the following entry:</div><br>
                <div><strong>' . $instance->obj->title . '</strong></div><br>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
	}

	/**
	 * Return the form for editing / inserting an instance title only
	 *
	 * @param $instance
	 * @param $action
	 * @param $insertAction
	 * @return string
	 */
	private function getTitleForm(InstanceInterface $instance, $action, $insertAction)
	{
		$label = $instance->obj->label;
		if (ContextMenu::CM_ACTION_EDIT !== $action) {
			$label = '';
		}

		return '<h1>' . ucfirst(str_replace('-', ' ', $action)) . ' ' . $label . '</h1>
                <label for="title"><b>Title</b></label>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->obj->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
                <input type="hidden" id="type" name="type" value="' . $instance->obj->type . '">
                <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . $instance->obj->title . '">
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
	}
}
