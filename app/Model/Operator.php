<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use SoftDeletes;

    const OPERATOR_EQ = 'eq';
    const OPERATOR_NE = 'ne';
    const OPERATOR_LT = 'lt';
    const OPERATOR_LE = 'le';
    const OPERATOR_GT = 'gt';
    const OPERATOR_GE = 'ge';
    const OPERATOR_IN = 'in';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get all the operators
     *
     * @return array
     */
    public static function getOperators()
    {
        return self::select()
            ->orderBy("operators.seq")
            ->get();
    }

    /**
     * Builds and returns a select listbox of Operators
     *
     * @param $selectedOperator
     * @return string
     */
    public static function getOperatorsList($selectedOperator = null)
    {
        $operators = self::getOperators();
        $html = '<select name="operator" id="operator">';
        foreach ($operators as $operator) {
            $selected = ($operator == $selectedOperator ? 'selected' : '');
            $html .= "<option value='{$operator->id}' $selected>" . strtoupper($operator->operator) . "</option>";
        }
        $html .= "</select>";

        return $html;
    }
}
