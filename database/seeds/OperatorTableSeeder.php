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
        $operator->seq = 1;
        $operator->tooltip = 'Equal to';
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_NE;
        $operator->seq = 2;
        $operator->tooltip = 'Not equal to';
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_LT;
        $operator->seq = 3;
        $operator->tooltip = 'Less than';
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_LE;
        $operator->seq = 4;
        $operator->tooltip = 'Less than or equal to';
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_GT;
        $operator->seq = 5;
        $operator->tooltip = 'Greater than';
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_GE;
        $operator->seq = 6;
        $operator->tooltip = 'Greater than or equal to';
        $operator->save();

        $operator = new Operator();
        $operator->operator = Operator::OPERATOR_IN;
        $operator->seq = 7;
        $operator->tooltip = 'In list';
        $operator->save();
    }
}
