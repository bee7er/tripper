<?php

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
    }
}
