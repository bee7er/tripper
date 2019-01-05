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
    use SoftDeletes, FormTrait;

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

    /**
     * Return the form for editing / inserting an instance
     * This base class funciton allows just the title only to be maintained
     *
     * @param $action
     * @param $insertAction
     * @param $targetInstanceId - the initiator of the event on insert
     * @return string
     */
    public function getEditForm($action, $clistId = null)
    {

        $title =  ucwords(str_replace('-', ' ', $action) . ' Constant');
        $html = '
            <input type="hidden" id="instanceId" name="clistId" value="' . $clistId . '">
            <input type="hidden" id="action" name="action" value="' . $action . '">
            <div class="md-form mb-5">
              <label for="title"><strong>Title</strong></label>
              <input type="text" placeholder="Enter title" name="title" id="title" class="focus" value="' . $title . '">
            </div>';


        return $this->getFormWrapper($title, $html);
    }
}
