<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\Condition;

class ConditionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('conditions')->delete();

        // Instances added elsewhere
    }
}
