<?php

use Faker\Generator as Faker;
use App\Models\Person;
$data = [
    'gender' => ['男', '女'],
    'department' => [
        '涂装工程部',
        '总装工程部',
        '生产运行部',
        '资产财务部',
        '保卫部',
        '规建部',
        '工务保障部',
    ],
    'edu'=>['专科', '本科', '硕士', '未知', '其他'],
];

$factory->define(Person::class, function (Faker $faker) use($data) {
    return [
        'name' => $faker->name,
        'identify' => $faker->creditCardNumber,
        'gender' => $data['gender'][random_int(0, count($data['gender']) - 1)],
        'department' => $data['department'][random_int(0, count($data['department']) - 1)],
        'contract_start' => $faker->date(),
        'contract_end' => $faker->date(),
        'education' => random_int(1, 5),
        'phone_number' => $faker->phoneNumber,
        'remark' => '这是备注',
        'spouse_name' => $faker->name,
    ];
});
