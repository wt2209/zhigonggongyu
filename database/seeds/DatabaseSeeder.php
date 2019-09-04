<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminPermissionsTableSeeder::class);
        $this->call(AdminTablesSeeder::class);
        if (App::environment('local')) {
            // The environment is local
            $this->call(TypesTableSeeder::class);
            $this->call(RoomsTableSeeder::class);
            $this->call(PeopleTableSeeder::class);
            $this->call(RecordsTableSeeder::class);
            $this->call(BillTypeTableSeeder::class);
            $this->call(BillTableSeeder::class);
            $this->call(AdminPermissionsTableSeeder::class);
    }

    }
}
