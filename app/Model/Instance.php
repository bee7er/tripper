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
     * Action diagram entries
     * @var array
     */
    public $entries = [];

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

        $instances = Instance::first()
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
        return Instance::where(['instances.id' => $id])
            ->join('blocks', 'blocks.id', '=', 'instances.block_id')
            ->leftjoin('subtypes', 'subtypes.id', '=', 'instances.subtype_id')
            ->select('instances.*',
                'blocks.label', 'blocks.type', 'blocks.contextMenuMap',
                'subtypes.subtype'
            )
            ->first();
    }

    /**
     * Get the parents children
     *
     * @return array
     */
    public static function getChildren($parentId)
    {
        $instances = Instance::where("instances.parent_id", $parentId)
            ->join('blocks', 'blocks.id', '=', 'instances.block_id')
            ->leftjoin('subtypes', 'subtypes.id', '=', 'instances.subtype_id')
            ->select('instances.*',
                'blocks.type','blocks.label','blocks.top1','blocks.top2','blocks.side','blocks.bottom1','blocks.bottom2','blocks.color','blocks.container','blocks.contextMenuMap',
                'subtypes.subtype'
            )
            ->orderBy("instances.seq")
            ->get();

        return $instances;
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
//        print '<pre/>'; print_r($instance);die;

        $depth++;
        if ($instance) {
            $children = self::getChildren($instance->id);
            $len = count($children);
            for ($i=0; $i<$len; $i++) {

                $child = $children[$i];

//                $child->block = Block::getBlock($child->block_id);
//                $child->subtype = null;
//                if ($child->subtype_id) {
//                    $child->subtype = Subtype::getSubtype($child->subtype_id);
//                }
                $child->entries[] = self::getOpeningLine($child, $depth, $colors);
                if ($child->container && $child->type !== Block::BLOCK_TYPE_ELSE) {
                    $child->entries[] = self::getContainerLine($child, $depth, $colors);
                }

                $tree[$child->id . '_start'] = $child;

                if ($child->container) {

                    // Let's have a new object
                    $child = clone $child;
                    $child->entries = [];

                    $nextChild = isset($children[$i + 1]) ? $children[$i + 1] : null;

                    $colors[] = $child->color;

                    if (!$child->collapsed) {
                        self::loadChildren($child, $tree, $depth, $colors);
                    }

                    if ($nextChild && $nextChild->type == Block::BLOCK_TYPE_ELSE) {
                        // Do not include an end entry because the condition continues
                    } elseif ($child->type == Block::BLOCK_TYPE_ELSE) {
                        $child->entries[] = self::getElseLine($child, $depth, $colors);
                        $tree[$child->id . '_else'] = $child;
                    } else {
                        $child->entries[] = self::getClosingLine($child, $depth, $colors);
                        $tree[$child->id . '_end'] = $child;
                    }

                    array_pop($colors);
                }
            }
        }
    }

    /**
     * Get the current prefix, comprising the depth and colors of previous levels
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    private static function getPrefix($depth, $colors)
    {
        $prefix = '';
        for ($i=0; $i<($depth - 1); $i++) {
            $prefix .= ("<span style='color: #{$colors[$i]}'>▎</span>");
        }

        return $prefix;
    }

    /**
     * Build and return the string representing the opening line
     *
     * @param Instance $instance
     * @param $depth
     * @param $colors
     * @return string
     */
    public static function getOpeningLine(Instance $instance, $depth, $colors)
    {
        $prefix = self::getPrefix($depth, $colors);

        // Insert before for containers but after for non-containers
        $title = 'Insert after';
        $insertAction = ContextMenu::INSERT_AFTER;
        $collapsed = '';
        if ($instance->container) {
            $title = 'Insert before';
            $insertAction = ContextMenu::INSERT_BEFORE;
            if ($instance->collapsed) {
                $collapsed = "- Collapsed";
            }
        }

        $addCssClass = $instance->isComplete() ? '': 'incomplete';

        return (
            "<div class='row-selected $addCssClass' id='{$instance->id}_$insertAction'>"
            . $prefix
            . "<span style='color: #{$instance->color}' title='$title'>"
            . $instance->top1
            . $instance->top2
            . '&nbsp;&nbsp;'
            . $instance->type
            . ($instance->container ? '' : ': ' . $instance->title) . $collapsed
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the first line of a container
     *
     * @param $instance
     * @param $depth
     * @param $colors
     * @return string
     */
    public static function getContainerLine($instance, $depth, $colors)
    {
        $prefix = self::getPrefix($depth, $colors);

        return (
            "<div class='row-selected' id='{$instance->id}_" . ContextMenu::INSERT_INSIDE . "'>"
            . $prefix
            . "<span style='color: #{$instance->color}' title='Insert inside'>"
            . $instance->side
            . '-&nbsp;&nbsp;'
            . $instance->title
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the closing of an else
     *
     * @param $instance
     * @param $depth
     * @param $colors
     * @return string
     */
    public static function getElseLine($instance, $depth, $colors)
    {
        $prefix = self::getPrefix($depth, $colors);

        return (
            "<div class='row-selected' id='{$instance->id}_" . ContextMenu::INSERT_AFTER . "'>"
            . $prefix
            . "<span style='color: #{$instance->color}' title='Insert after'>"
            . $instance->bottom1
            . $instance->bottom2
            . '&nbsp;&nbsp;'
            . Block::BLOCK_TYPE_CONDITION
            . ': End '
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the closing line
     *
     * @param $instance
     * @param $depth
     * @param $colors
     * @return string
     */
    public static function getClosingLine($instance, $depth, $colors)
    {
        $prefix = self::getPrefix($depth, $colors);

        return (
            "<div class='row-selected' id='{$instance->id}_" . ContextMenu::INSERT_AFTER . "'>"
            . $prefix
            . "<span style='color: #{$instance->color}' title='Insert after'>"
            . $instance->bottom1
            . $instance->bottom2
            . '&nbsp;&nbsp;'
            . $instance->type
            . ': End '
            . $instance->title
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
        if (($missingActions = $this->getMissingActions())) {
            // At least one missing action
            return false;
        }

        return true;
    }

    /**
     * Checks what is missing and returns an appropriate ContextMenu option
     * for each additional action that is needed
     *
     * @return bool
     */
    public function getMissingActions()
    {
        $actions = [];
        if (Subtype::SUBTYPE_SNIPPET === $this->subtype
            && !isset($this->snippetTrip_id)
        ) {
            // A snippet action but no referenced trip
            $actions[] = ContextMenu::CM_ACTION_SELECT_SNIPPET;
        }

        return $actions;
    }
}
