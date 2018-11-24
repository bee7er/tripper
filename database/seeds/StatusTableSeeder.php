<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\Status;

class StatusTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('statuses')->delete();
        
        $status = new Status();
        $status->label = 'Repayment';
        $status->value = 'repayment';
        $status->save();

        $status = new Status();
        $status->label = 'Interest only';
        $status->value = 'interest only';
        $status->save();
    }
}
