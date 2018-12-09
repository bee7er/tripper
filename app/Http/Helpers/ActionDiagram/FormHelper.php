<?php

namespace App\Http\Helpers\ActionDiagram;

use App\Model\Block;
use App\Model\ContextMenu;

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
	public function getFormByTypeAndAction($instance, $action, $insertAction)
	{
		switch ($action) {
			case ContextMenu::CM_ACTION_EDIT:
				switch ($instance->type) {
					case Block::BLOCK_TYPE_COMMENT:
						return $this->getTitleForm($instance, $action, $insertAction);

					case Block::BLOCK_TYPE_ACTION:
						return $this->getActionForm($instance, $action, $insertAction);

					default:
						break;
				}
				break;

			case ContextMenu::CM_ACTION_DELETE:
				return $this->getDeleteForm($instance, $action);

			case ContextMenu::CM_ACTION_INSERT_ACTION:
				return $this->getActionForm($instance, $action, $insertAction);

			case ContextMenu::CM_ACTION_INSERT_COMMENT:
			case ContextMenu::CM_ACTION_INSERT_SEQUENCE:
				return $this->getTitleForm($instance, $action, $insertAction);

			default:
				break;
		}

		return false;
	}

	/**
	 * Return the form for editing / inserting an action
	 *
	 * @param $instance
	 * @param $action
	 * @param $insertAction
	 * @return string
	 */
	private function getActionForm($instance, $action, $insertAction)
	{
		return '<h1>'.ucfirst($action).' Action</h1>
                <label for="title"><b>Title</b></label>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
                <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . ($action === ContextMenu::CM_ACTION_INSERT_ACTION ? '' : $instance->title) . '">
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
	}

	/**
	 * Return the form for deleting an instance
	 *
	 * @param null $instance
	 * @param $action
	 * @return string
	 */
	private function getDeleteForm($instance, $action)
	{
		return '<h1>' . ucfirst(str_replace('-', '', $action)) . ' Entry</h1>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <div>Are you sure you want to delete the following entry:</div><br>
                <div><strong>' . $instance->title . '</strong></div><br>
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
	private function getTitleForm($instance, $action, $insertAction)
	{
		$title = $instance->title;
		if (ContextMenu::CM_ACTION_EDIT !== $action) {
			$title = '';
		}

		return '<h1>' . ucfirst(str_replace('-', ' ', $action)) . '</h1>
                <label for="title"><b>Title</b></label>
                <input type="hidden" id="instanceId" name="instanceId" value="' . ($instance ? $instance->id : '') . '">
                <input type="hidden" id="action" name="action" value="' . $action . '">
                <input type="hidden" id="insertAction" name="insertAction" value="' . $insertAction . '">
                <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . $title . '">
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                <button type="button" class="btn" onclick="submitForm()">Submit</button>';
	}
}
