<?php

use App\Model\Clist;
use App\Model\ClistConstant;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\Constant;

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
    }
}
