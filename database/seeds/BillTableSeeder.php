<?php

use Illuminate\Database\Seeder;

class BillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = factory(\App\Models\Bill::class, 3000)->make()->toArray();
        \App\Models\Bill::insert($data);
    }
}
