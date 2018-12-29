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
}
