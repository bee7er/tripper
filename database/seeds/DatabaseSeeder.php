<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Add calls to Seeders here
        $this->call(UserTableSeeder::class);
//        $this->command->info('Admin User created with username admin@admin.com and password admin');
//        $this->command->info('Test User created with username user@user.com and password user');

		$this->call(LanguageTableSeeder::class);
		$this->call(TemplateTableSeeder::class);
		$this->call(ResourceTableSeeder::class);

        $this->call(OperatorTableSeeder::class);
        $this->call(ContextTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(ConstantTableSeeder::class);
        $this->call(ConditionTableSeeder::class);
        $this->call(TripTableSeeder::class);
        $this->call(SubtypeTableSeeder::class);
        $this->call(QuestionTableSeeder::class);
        $this->call(BlockTableSeeder::class);

        $this->call(InstanceTableSeeder::class);

        $this->call(JourneyTableSeeder::class);

        Model::reguard();
    }
}