<?php

use App\Model\Clist;
use App\Model\ClistConstant;
use Illuminate\Database\Seeder;

use App\Model\Constant;
use Illuminate\Support\Facades\DB;

class ConstantTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('constants')->delete();

        $constant = new Constant();
        $constant->label = 'Loan limit';
        $constant->value = '100000';
        $constant->save();

        $yConstant = new Constant();
        $yConstant->label = 'Yes';
        $yConstant->value = 'y';
        $yConstant->save();

        $nConstant = new Constant();
        $nConstant->label = 'No';
        $nConstant->value = 'n';
        $nConstant->save();

        $repaymentConstant = new Constant();
        $repaymentConstant->label = 'Repayment';
        $repaymentConstant->value = 'repayment';
        $repaymentConstant->save();

        $interestConstant = new Constant();
        $interestConstant->label = 'Interest only';
        $interestConstant->value = 'interest only';
        $interestConstant->save();

        $clist = new Clist();
        $clist->label = 'Yes/No';
        $clist->save();

        $clistConstant = new ClistConstant();
        $clistConstant->clist_id = $clist->id;
        $clistConstant->constant_id = $yConstant->id;
        $clistConstant->save();

        $clistConstant = new ClistConstant();
        $clistConstant->clist_id = $clist->id;
        $clistConstant->constant_id = $nConstant->id;
        $clistConstant->save();

        $clist = new Clist();
        $clist->label = 'Repayment type';
        $clist->save();

        $clistConstant = new ClistConstant();
        $clistConstant->clist_id = $clist->id;
        $clistConstant->constant_id = $repaymentConstant->id;
        $clistConstant->save();

        $clistConstant = new ClistConstant();
        $clistConstant->clist_id = $clist->id;
        $clistConstant->constant_id = $interestConstant->id;
        $clistConstant->save();
    }
}
