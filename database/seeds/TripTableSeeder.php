<?php

use Illuminate\Database\Seeder;
use App\Model\Trip;

class TripTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('trips')->delete();

        $trip = new Trip();
        $trip->title = 'First trip';
        $trip->save();
    }
}
