<?php

use Faker\Generator as Faker;


$factory->define(\App\Models\Bill::class, function (Faker $faker) {
    $rooms = \App\Models\Room::pluck('title')->toArray();
    $types = \App\Models\BillType::pluck('id')->toArray();
    $roomsCount = count($rooms);
    $typesCount = count($types);
    return [
        'location' => $rooms[random_int(0, $roomsCount - 1)],
        'name' => $faker->name,
        'bill_type_id' => $types[random_int(0, $typesCount - 1)],
        'cost' => random_int(200, 100000) / 100,
        'input_user_id' => 1,
    ];
});
