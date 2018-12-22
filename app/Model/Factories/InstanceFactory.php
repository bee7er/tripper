<?php

namespace App\Model\Factories;

use App\Model\Block;
use App\Model\Instance;
use App\Model\Instances\Action;
use App\Model\Instances\Comment;
use App\Model\Instances\Condition;
use App\Model\Instances\ElseInstance;
use App\Model\Instances\Iteration;
use App\Model\Instances\Sequence;

/**
 * Class InstanceFactory
 * Knows how to instantiate and return a particular type of instance
 * @package App\Model\Factories
 */
class InstanceFactory
{
    /**
     * Get a controller instance; a sequence
     *
     * @return Instance
     */
    public static function getControllerInstance($tripId = 0)
    {
        if (0 !== $tripId) {
            $instance = Instance::where(['trip_id' => $tripId, 'seq' => 0])->firstOrFail();
        } else {
            // Just find the first one in the db
            $instances = Instance::first()
                ->where("instances.id", ">=", $tripId)
                ->orderBy("id", "ASC")
                ->limit(1)
                ->get();

            $instance = null;
            if ($instances && count($instances) > 0) {
                $instance = $instances[0];
            }
        }
        if (!$instance) {
            throw new \Exception('Could not instantiate controller instance');
        }
        $controller = self::getInstance($instance->id);

        return $controller;
    }

    /**
     * Get an instance of a particular type
     *
     * @param $id
     * @return Action|Comment|Condition|ElseInstance|Iteration|Sequence|null
     * @throws \Exception
     */
    public static function getInstance($id)
    {
        $instance = Instance::getInstance($id);

        switch ($instance->type) {
            case Block::BLOCK_TYPE_ACTION:
                return new Action($instance);

            case Block::BLOCK_TYPE_COMMENT:
                return new Comment($instance);

            case Block::BLOCK_TYPE_CONDITION:
                return new Condition($instance);

            case Block::BLOCK_TYPE_ELSE:
                return new ElseInstance($instance);

            case Block::BLOCK_TYPE_ITERATION:
                return new Iteration($instance);

            case Block::BLOCK_TYPE_SEQUENCE:
                return new Sequence($instance);

            default:
                throw new \Exception("Unexpected instance type '{$instance->type}'");
                break;
        }

        return null;
    }
}
