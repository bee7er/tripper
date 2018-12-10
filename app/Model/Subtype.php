<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtype extends Model
{
    use SoftDeletes;

    const SUBTYPE_IMAGE = 'img';
    const SUBTYPE_INSTRUCTION = 'ins';
    const SUBTYPE_QUESTION = 'qus';
    const SUBTYPE_SNIPPET = 'snp';
    const SUBTYPE_TEXT = 'txt';
    const SUBTYPE_VIDEO = 'vid';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get the subtype
     *
     * @param $subtypeId
     * @return mixed
     */
    public static function getSubtype($subtypeId)
    {
        return self::where(['id' => $subtypeId])->firstOrFail();
    }

    /**
     * Get all the subtypes
     *
     * @return array
     */
    public static function getSubtypes()
    {
        $subtypes = self::select()
            ->orderBy("subtypes.label")
            ->get();

        return $subtypes;
    }

    /**
     * Build and return a select list
     */
    public static function getSubtypeList()
    {
        $list = [];
        $subtypes = self::getSubtypes();
        if ($subtypes) {
            foreach ($subtypes as $subtype) {
                $list[$subtype->id] = $subtype->label;
            }
        }

        return $list;
    }
}
