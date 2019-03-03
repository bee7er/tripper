<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\Context;

class ContextTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('contexts')->delete();

        $context = new Context();
        $context->context = Context::CONTEXT_RESPONSE;     // Responses
        $context->seq = 1;
        $context->tooltip = 'Response fields';
        $context->save();

        $context = new Context();
        $context->context = Context::CONTEXT_CONSTANT;     // Constant
        $context->seq = 3;
        $context->tooltip = 'Constant value';
        $context->save();

        $context = new Context();
        $context->context = Context::CONTEXT_LIST;     // Lists
        $context->seq = 4;
        $context->tooltip = 'List of values';
        $context->save();
    }
}
