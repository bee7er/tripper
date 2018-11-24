<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use SoftDeletes;

    const OPERATOR_EQ = 'eql';
    const OPERATOR_LT = 'lt';
    const OPERATOR_LE = 'le';
    const OPERATOR_GT = 'gt';
    const OPERATOR_GE = 'ge';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');
}
