<?php

namespace App\Model;

use App\Model\Factories\InstanceFactory;
use App\Model\Instances\InstanceInterface;
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
                'blocks.type','blocks.label','blocks.top1','blocks.top2','blocks.side','blocks.bottom1',
                'blocks.bottom2','blocks.color','blocks.container','blocks.contextMenuMap',
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
    public static function loadChildren(InstanceInterface $instance, &$tree, $depth = 0, $colors = [])
    {
        $depth++;
        if ($instance) {
            $children = self::getChildren($instance->instance->id);
            $len = count($children);
            for ($i=0; $i<$len; $i++) {

                $child = $children[$i];
                $childInstance = InstanceFactory::getInstance($child->id);

                $childInstance->entries[] = $childInstance->getOpeningLine($depth, $colors);

                if ($childInstance->instance->container && $childInstance->instance->type !== Block::BLOCK_TYPE_ELSE) {
                    $childInstance->entries[] = $childInstance->getContainerLine($depth, $colors);
                }

                $tree[$childInstance->instance->id . '_start'] = $childInstance;

                if ($childInstance->instance->container) {

                    // Let's have a new object
                    $childInstance = clone $childInstance;
                    $childInstance->entries = [];
                    $colors[] = $childInstance->instance->color;

                    if (!$childInstance->instance->collapsed) {
                        self::loadChildren($childInstance, $tree, $depth, $colors);
                    }

                    // Special check for else block
                    $nextChild = isset($children[$i + 1]) ? $children[$i + 1] : null;
                    if ($nextChild && $nextChild->type == Block::BLOCK_TYPE_ELSE) {
                        // Do not include an end entry because the condition continues
                    } else {
                        $childInstance->entries[] = $childInstance->getClosingLine($depth, $colors);
                        $tree[$childInstance->instance->id . '_end'] = $childInstance;
                    }

                    array_pop($colors);
                }
            }

            //print '<pre/>'; print_r($tree);die;
        }
    }
}
