<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instance extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get the parents children
     *
     * @return Instance
     */
    public static function getController($id)
    {
        $instance = Instance::first(
            array(
                'instances.id',
                'instances.trip_id',
                'instances.block_id',
                'instances.subtype_id',
                'instances.question_id',
                'instances.condition_id',
                'instances.parent_id',
                'instances.seq',
                'instances.title',
                'instances.deleted_at',
            )
        )
            ->where("instances.id", $id)
            ->get();

        return $instance[0];
    }

    /**
     * Load instance children, recursively
     *
     * @param $instance
     * @param $tree
     * @param $depth
     */
    public static function loadChildren($instance, &$tree, $depth = 0)
    {
        //print '<pre/>'; print_r($instance);die;

        $depth++;
        if ($instance) {
            $children = self::getChildren($instance->id);
            $len = count($children);
            for ($i=0; $i<$len; $i++) {

                $child = $children[$i];

                $nextChild = isset($children[$i + 1]) ? $children[$i + 1] : null;

                $nextBlock = null;
                if ($nextChild) {
                    $nextBlock = Block::getBlock($nextChild->block_id);
                }

                $child->block = Block::getBlock($child->block_id);
                $child->line = self::getOpeningLine($child, $child->block, $depth);

                $tree[$child->id . '_start'] = $child;

                if ($child->block->container) {

                    // Let's have a new object
                    $child = clone $child;

                    self::loadChildren($child, $tree, $depth);

                    if ($nextBlock && $nextBlock->type == Block::BLOCK_TYPE_ELSE) {
                        // Do not include an end entry because the condition continues
                    } elseif ($child->block->type == Block::BLOCK_TYPE_ELSE) {
                        $child->line = self::getElseLine($child, $child->block, $depth);
                        $tree[$child->id . '_else'] = $child;
                    } else {
                        $child->line = self::getClosingLine($child, $child->block, $depth);
                        $tree[$child->id . '_end'] = $child;
                    }
                }
            }
        }
    }

    /**
     * Build and return the string representing the opening line
     *
     * @return array
     */
    public static function getOpeningLine($instance, $block, $depth)
    {
        return (
            str_repeat('| ', $depth - 1)
            . $block->top1
            . $block->top2
            . ' '
            . $block->type
            . ': '
            . $instance->title
        );
    }

    /**
     * Build and return the string representing the opening line
     *
     * @return array
     */
    public static function getElseLine($instance, $block, $depth)
    {
        return (
            str_repeat('| ', $depth - 1)
            . $block->bottom1
            . $block->bottom2
            . Block::BLOCK_TYPE_CONDITION
            . ': End '
            . $instance->title
        );
    }

    /**
     * Build and return the string representing the closing line
     *
     * @return array
     */
    public static function getClosingLine($instance, $block, $depth)
    {
        return (
            str_repeat('| ', $depth - 1)
            . $block->bottom1
            . $block->bottom2
            . $block->type
            . ': End '
            . $instance->title
        );
    }

    /**
     * Get the parents children
     *
     * @return array
     */
    public static function getChildren($parentId)
    {
        $instances = Instance::select(
            array(
                'instances.id',
                'instances.trip_id',
                'instances.block_id',
                'instances.subtype_id',
                'instances.question_id',
                'instances.condition_id',
                'instances.parent_id',
                'instances.seq',
                'instances.title',
                'instances.deleted_at',
            )
        )
            ->where("instances.parent_id", $parentId)
            ->orderBy("instances.seq")
            ->get();

        return $instances;
    }
}
