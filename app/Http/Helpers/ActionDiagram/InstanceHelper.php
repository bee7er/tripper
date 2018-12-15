<?php

namespace App\Http\Helpers\ActionDiagram;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Model\Instance;
use Illuminate\Support\Facades\Log;

class InstanceHelper
{
	private static $validFields = [
		Block::BLOCK_TYPE_ACTION => ['title', 'subtype_id'],
		Block::BLOCK_TYPE_COMMENT => ['title'],
		Block::BLOCK_TYPE_CONDITION => ['title'],
		Block::BLOCK_TYPE_ITERATION => ['title'],
		Block::BLOCK_TYPE_SEQUENCE => ['title'],
	];

	/**
	 * Save an instance, with update or insert
	 *
	 * @param $params
	 * @return mixed
	 */
	public function save($params)
	{
		$success = null;
		switch ($params['action']) {
			case ContextMenu::CM_ACTION_EDIT:
				return $this->update($params);

			case ContextMenu::CM_ACTION_INSERT_ACTION:
				return $this->insert($params, Block::BLOCK_TYPE_ACTION);

			case ContextMenu::CM_ACTION_INSERT_COMMENT:
				return $this->insert($params, Block::BLOCK_TYPE_COMMENT);

			case ContextMenu::CM_ACTION_INSERT_CONDITION:
				return $this->insert($params, Block::BLOCK_TYPE_CONDITION);

			case ContextMenu::CM_ACTION_INSERT_ITERATION:
				return $this->insert($params, Block::BLOCK_TYPE_ITERATION);

			case ContextMenu::CM_ACTION_INSERT_SEQUENCE:
				return $this->insert($params, Block::BLOCK_TYPE_SEQUENCE);

			default:
				break;
		}

		return [
			'success' => false,
			'data'   => 'Unknown action',
		];
	}

	/**
	 * Update an instance
	 *
	 * @param $formData
	 * @return array
	 */
	public function update($params)
	{
		$success = null;
		$messages = [];
		try {
			if (!isset(self::$validFields[$params['type']])) {
				throw new \Exception('Type not supported for this function');
			}

			$instance = Instance::find($params['instanceId']);
			// Save all parameters by name
			foreach ($params as $field => $param) {
				// If a valid field then update the instance with each one
				if (in_array($field, self::$validFields[$params['type']])) {
					$instance->$field = $params[$field];
				}
			}

			$instance->save();
			$messages[] = "Updated '{$instance->title}'";
			$success = true;
		} catch (\Exception $e) {
			$messages[]  = $e->getMessage() . ' For more info see log.';
			$success = false;
			Log::info("Error updating instance with id {$params['instanceId']}", [
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
	 * Update an instance to point at a snippet
	 *
	 * @param $formData
	 * @return array
	 */
	public function setSnippet($params)
	{
		$success = null;
		$messages = [];
		try {
			if (Block::BLOCK_TYPE_ACTION !== $params['type']) {
				throw new \Exception("Type {$params['type']} not supported for this function");
			}

			if (!$params['snippetId']) {
				throw new \Exception("Snippet id not supplied");
			}

			$snippetInstance = Instance::find($params['snippetId']);
			if (!$snippetInstance) {
				throw new \Exception("Snippet instance not found for id {$params['snippetId']}");
			}

			$instance = Instance::find($params['instanceId']);
			$instance->snippetTrip_id = $params['snippetId'];
			$instance->save();
			$messages[] = "Updated '{$instance->title}'";
			$success = true;
		} catch (\Exception $e) {
			$messages[]  = $e->getMessage() . ' For more info see log.';
			$success = false;
			Log::info("Error updating instance id {$params['instanceId']} with snippet id {$params['snippetId']}", [
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
	public function insert($params, $blockType)
	{
		$success = null;
		$messages = [];
		try {
			$sibling = Instance::find($params['instanceId']);
			$block = Block::where('type','=',$blockType)->first();

			// Where is this instance going?
			$parentId = $seq = null;
			$where = null;
			switch ($params['insertAction']) {
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

			$newInstance = new Instance();
			$newInstance->id = null;
			$newInstance->trip_id = $sibling->trip_id;
			$newInstance->parent_id = $parentId;
			$newInstance->seq = $seq;
			$newInstance->block_id = $block->id;
			$newInstance->title = $params['title'];
			$newInstance->save();
			// NB AFTER Resequence the siblings so we can keep the order
			$this->resequenceInstanceChildren($parentId);
			$messages[] = "Inserted '" . $newInstance->title . "' $where";
			$success = true;
		} catch (\Exception $e) {
			$messages[]  = $e->getMessage() . ' For more info see log.';
			$success = false;
			Log::info('Error inserting data: ' . $params['title'], [
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
	 * Delete an instance, and all its contents
	 *
	 * @param $params
	 * @return array
	 */
	public function delete($params)
	{
		$success = null;
		$messages = [];
		try {
			$instance = Instance::find($params['instanceId']);
			if ($instance) {
				if ($instance->protected) {
					$messages[]  = 'This entry cannot be deleted';
					$success = false;
				} else {
					// Ok to delete
					$instance->delete();
					$messages[] = "Deleted instance '{$instance->title}'";
					$success = true;
				}
			} else {
				$messages[] = "Could not find instance '{$params['instanceId']}'";
				$success = false;
			}
		} catch (\Exception $e) {
			$messages[]  = $e->getMessage() . ' For more info see log.';
			$success = false;
			Log::info("Error deleting instance with id {$params['instanceId']}", [
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
}
