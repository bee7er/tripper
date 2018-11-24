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
}
