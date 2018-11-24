<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    const QUESTION_TYPE_SELECT = 'sel';
    const QUESTION_TYPE_TEXT = 'txt';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');
}
