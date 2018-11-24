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
    public static function getController()
    {
        $instance = Instance::select(
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
            ->where("instances.parent_id", null)
            ->orderBy("instances.seq")
            ->get();

        return $instance[0];
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
