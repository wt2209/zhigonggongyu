<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Record::class, function (Faker $faker) {
    $start = $faker->dateTimeBetween('-3 years', '-120 days');
    $end = date_add($start, date_interval_create_from_date_string('-1 year'));
    return [
        'start_at'=>$start,
        'end_at' => $end,
    ];
});
