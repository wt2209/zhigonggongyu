<?php

use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = factory(\App\Models\Person::class,1800)->make()->toArray();
        \App\Models\Person::insert($data);
    }
}
