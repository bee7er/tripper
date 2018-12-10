<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    // Date response
    const QUESTION_TYPE_DATE = 'dte';
    // Datetime response
    const QUESTION_TYPE_DATETIME = 'dtm';
    // Numeric response
    const QUESTION_TYPE_NUMERIC = 'nbr';
    // Yes/No, High/Low, etc
    const QUESTION_TYPE_SELECT = 'sel';
    // Textual response, free format
    const QUESTION_TYPE_TEXT = 'txt';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');
}
