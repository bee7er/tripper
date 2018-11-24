<?php
use Illuminate\Database\Seeder;
use App\Notice;

class NoticeTableSeeder extends Seeder {

    public function run()
    {
        DB::table('notices')->delete();

        $notice = new Notice();
        $notice->url = 'https://www.stashmedia.tv/skateboarding-spider-takes-bathroom-stash-magazine/';
        $notice->description = 'London animator Russ Etheridge turns his bathroom into an arachnid skatepark in this fun new studio film from Animade called “Bathroom Boarder.”';
        $notice->save();
    }

}
