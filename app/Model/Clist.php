<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Clist - list is a keyword, so we call it clist, as in a list of constants
 * @package App\Model
 */
class Clist extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get the constants for the specified list
     *
     * @return array
     */
    public static function getListEntries($listId)
    {
        return ClistConstant::where("clist_constants.clist_id", $listId)
            ->join('constants', 'constants.id', '=', 'clist_constants.constant_id')
            ->orderBy("constants.label")->get();
    }
}
