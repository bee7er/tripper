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
    const SUBTYPE_TEXT = 'txt';
    const SUBTYPE_VIDEO = 'vid';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');
}
