<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    // Currency response
    const QUESTION_TYPE_CURRENCY = 'cur';
    // Date response
    const QUESTION_TYPE_DATE = 'dte';
    // Datetime response
    const QUESTION_TYPE_DATETIME = 'dtm';
    // Numeric response
    const QUESTION_TYPE_NUMERIC = 'nbr';
    // Yes/No, High/Low, etc
    const QUESTION_TYPE_SELECT = 'lst';
    // Percentage
    const QUESTION_TYPE_PERCENTAGE = 'per';
    // Textual response, free format
    const QUESTION_TYPE_TEXT = 'txt';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * @var array
     */
    private static $types = [
        'Currency' => self::QUESTION_TYPE_CURRENCY,
        'Date' => self::QUESTION_TYPE_DATE,
        'DateTime' => self::QUESTION_TYPE_DATETIME,
        'Numeric' => self::QUESTION_TYPE_NUMERIC,
        'Select' => self::QUESTION_TYPE_SELECT,
        'Percentage' => self::QUESTION_TYPE_PERCENTAGE,
        'Text' => self::QUESTION_TYPE_TEXT
    ];

    /**
     * Builds and returns a select listbox of Question types
     *
     * @param $selectedType
     * @return string
     */
    public static function getTypes($selectedType)
    {
        $html = '<select class="form-control" name="type" id="type">';
        foreach (self::$types as $name => $type) {
            $selected = ($type == $selectedType ? 'selected' : '');
            $html .= "<option value='$type' $selected>$name</option>";
        }
        $html .= "</select>";

        return $html;
    }
}
