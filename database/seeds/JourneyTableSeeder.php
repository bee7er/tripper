<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\Journey;
use App\Model\Trip;
use App\User;

class JourneyTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('journeys')->delete();

        $user = User::where('email', 'contact_bee@yahoo.com')->firstOrFail();
        $trip = Trip::where('title', 'First trip')->firstOrFail();

        $journey = new Journey();
        $journey->user_id = $user->id;
        $journey->trip_id = $trip->id;
        $journey->save();
    }
}
