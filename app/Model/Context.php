<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Context extends Model
{
    use SoftDeletes;

    const CONTEXT_CONSTANT = 'con';
    const CONTEXT_LIST = 'lst';
    const CONTEXT_RESPONSE = 'rsp';
    const CONTEXT_STATUS = 'sts';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get all the contexts
     *
     * @return array
     */
    public static function getContexts()
    {
        return self::select()
            ->orderBy("contexts.seq")
            ->get();
    }

    /**
     * Builds and returns a select listbox of context
     *
     * @param $selectedContext
     * @return string
     */
    public static function getContextsList($selectedContext = null)
    {
        $contexts = self::getContexts();
        $html = '<select name="context" id="context" title="Select context">';
        foreach ($contexts as $context) {
            $selected = ($context == $selectedContext ? 'selected' : '');
            $html .= "<option value='{$context->id}' $selected>" . strtoupper($context->context) . "</option>";
        }
        $html .= "</select>";

        return $html;
    }
}
