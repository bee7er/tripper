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
     * Return the form for editing / inserting a constant
     *
     * @param $constant
     * @param $action
     * @return string
     */
    public function getEditForm($constant, $action)
    {

        $title =  ucwords(str_replace('-', ' ', $action));
        $label =  $constant ? $constant->label : '';
        $value =  $constant ? $constant->value : '';

        $html = '
            <input type="hidden" id="clistId" name="clistId" value="' . $this->id . '">
            <input type="hidden" id="action" name="action" value="' . $action . '">
            <div class="md-form mb-5">
              <label for="label"><strong>Label</strong></label>
              <input type="text" placeholder="Enter label" name="label" id="label" class="focus" value="' . $label . '">
            </div>
            <div class="md-form mb-5">
              <label for="label"><strong>Value</strong></label>
              <input type="text" placeholder="Enter value" name="label" id="value" class="focus" value="' . $value . '">
            </div>';


        return $this->getFormWrapper($title, $html);
    }
}
