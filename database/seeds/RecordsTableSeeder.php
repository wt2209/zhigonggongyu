<?php

use Illuminate\Database\Seeder;

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $people = \App\Models\Person::all();
        $rooms = \App\Models\Room::all();

        $data = [];
        foreach ($people as $person) {
            $room = $rooms->random();
            $tmp = factory(\App\Models\Record::class, 1)->make([
                'person_id'=>$person->id,
                'room_id' => $room->id,
                'type_id' => $room->type_id,
            ])->toArray();
            $data = array_merge($data, $tmp);
        }
        \App\Models\Record::insert($data);
    }
}
