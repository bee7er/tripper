<?php

namespace App\Model;

use App\Model\Factories\InstanceFactory;
use App\Model\Instances\InstanceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Instance extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

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
                'blocks.type','blocks.label','blocks.top1','blocks.top2','blocks.side','blocks.bottom1',
                'blocks.bottom2','blocks.color','blocks.container','blocks.contextMenuMap',
                'subtypes.subtype'
            )
            ->first();
    }

    /**
     * Get the children of the parent specified by the parent id parameter
     *
     * @return array
     */
    public static function getChildren($parentId)
    {
        return Instance::where("instances.parent_id", $parentId)->orderBy("instances.seq")->get();
    }

    /**
     * Load instance children, recursively
     *
     * @param $instance
     * @param $tree
     * @param $depth
     */
    public static function loadChildren(InstanceInterface $instance, &$tree, $depth = 0, $colors = [])
    {
        $depth++;
        if ($instance) {
            $children = self::getChildren($instance->obj->id);
            $len = count($children);
            for ($i=0; $i<$len; $i++) {

                $childInstance = InstanceFactory::getInstance($children[$i]->id);

                $childInstance->entries[] = $childInstance->getOpeningLine($depth, $colors);

                if ($childInstance->obj->container && $childInstance->obj->type !== Block::BLOCK_TYPE_ELSE) {
                    $childInstance->entries[] = $childInstance->getContainerLine($depth, $colors);
                }

                $tree[$childInstance->obj->id . '_start'] = $childInstance;

                if ($childInstance->obj->container) {
                    // Let's have a new object
                    $childInstance = clone $childInstance;
                    $childInstance->entries = [];

                    $colors[] = $childInstance->obj->color;

                    if (!$childInstance->obj->collapsed) {
                        self::loadChildren($childInstance, $tree, $depth, $colors);
                    }

                    // Special check for else block
                    $nextChildInstance = null;
                    if (isset($children[$i + 1])) {
                        $nextChildInstance = InstanceFactory::getInstance($children[$i + 1]->id);
                    }

                    if ($nextChildInstance && Block::BLOCK_TYPE_ELSE === $nextChildInstance->obj->type) {
                        // Do not include an end entry because the condition continues
                    } else {
                        $childInstance->entries[] = $childInstance->getClosingLine($depth, $colors);
                        $tree[$childInstance->obj->id . '_end'] = $childInstance;
                    }

                    array_pop($colors);
                }
            }
        }
    }
}
