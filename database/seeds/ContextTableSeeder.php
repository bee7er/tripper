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
        $context->save();

        $context = new Context();
        $context->context = Context::CONTEXT_STATUS;     // Status
        $context->save();

        $context = new Context();
        $context->context = Context::CONTEXT_CONSTANT;     // Constant
        $context->save();

        $context = new Context();
        $context->context = Context::CONTEXT_LIST;     // Lists
        $context->save();
    }
}
