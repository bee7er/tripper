<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\Operator;

class OperatorTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('operators')->delete();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_EQ;
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_LE;
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_LT;
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_GE;
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_GT;
        $operator->save();
    }
}
