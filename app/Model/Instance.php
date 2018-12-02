<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    public static function getController($id = 0)
    {
        if (0 !== $id) {
            return self::where(['trip_id' => $id,'seq' => 0])->firstOrFail();
        }

        $instances = Instance::first(
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
            ->where("instances.id", ">=", $id)
            ->orderBy("id", "ASC")
            ->limit(1)
            ->get();

        if ($instances && count($instances) > 0) {
            return $instances[0];
        }

        return null;
    }

    /**
     * Get the instance
     *
     * @return Instance
     */
    public static function getInstance($id)
    {
        $instances = DB::table('instances')
            ->join('blocks', 'blocks.id', '=', 'instances.block_id')
            ->select('instances.*', 'blocks.*')
            ->where(['instances.id' => $id])
            ->get();

        if ($instances && count($instances) > 0) {
            return $instances[0];
        }

        return null;
    }

    /**
     * Load instance children, recursively
     *
     * @param $instance
     * @param $tree
     * @param $depth
     */
    public static function loadChildren($instance, &$tree, $depth = 0, $colors = [])
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
                $child->line = self::getOpeningLine($child, $child->block, $depth, $colors);

                $tree[$child->id . '_start'] = $child;

                if ($child->block->container) {

                    // Let's have a new object
                    $child = clone $child;

                    $colors[] = $child->block->color;

                    self::loadChildren($child, $tree, $depth, $colors);

                    if ($nextBlock && $nextBlock->type == Block::BLOCK_TYPE_ELSE) {
                        // Do not include an end entry because the condition continues
                    } elseif ($child->block->type == Block::BLOCK_TYPE_ELSE) {
                        $child->line = self::getElseLine($child, $child->block, $depth, $colors);
                        $tree[$child->id . '_else'] = $child;
                    } else {
                        $child->line = self::getClosingLine($child, $child->block, $depth, $colors);
                        $tree[$child->id . '_end'] = $child;
                    }

                    array_pop($colors);
                }
            }
        }
    }

    /**
     * Build and return the string representing the opening line
     *
     * @return array
     */
    public static function getOpeningLine($instance, $block, $depth, $colors)
    {
        $prefix = '';
        for ($i=0; $i<($depth - 1); $i++) {
            $prefix .= ("<span style='color: #{$colors[$i]}'>▎</span>");
        }

        return (
            $prefix
            . "<span style='color: #{$block->color}'>"
            . $block->top1
            . $block->top2
            . '&nbsp;&nbsp;'
            . $block->type
            . ': '
            . $instance->title
            . "</span>"
        );
    }

    /**
     * Build and return the string representing the opening line
     *
     * @return array
     */
    public static function getElseLine($instance, $block, $depth, $colors)
    {
        $prefix = '';
        for ($i=0; $i<($depth - 1); $i++) {
            $prefix .= ("<span style='color: #{$colors[$i]}'>▎</span>");
        }

        return (
            $prefix
            . "<span style='color: #{$block->color}'>"
            . $block->bottom1
            . $block->bottom2
            . '&nbsp;&nbsp;'
            . Block::BLOCK_TYPE_CONDITION
            . ': End '
            . "</span>"
        );
    }

    /**
     * Build and return the string representing the closing line
     *
     * @return array
     */
    public static function getClosingLine($instance, $block, $depth, $colors)
    {
        $prefix = '';
        for ($i=0; $i<($depth - 1); $i++) {
            $prefix .= ("<span style='color: #{$colors[$i]}'>▎</span>");
        }

        return (
            $prefix
            . "<span style='color: #{$block->color}'>"
            . $block->bottom1
            . $block->bottom2
            . '&nbsp;&nbsp;'
            . $block->type
            . ': End '
            . $instance->title
            . "</span>"
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
