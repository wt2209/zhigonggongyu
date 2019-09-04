<?php

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesTableSeeder extends Seeder
{

    protected $types = [
        ['title' => '大学生'],
        ['title' => '职工'],
        ['title' => '派遣工'],
        ['title' => '测试1'],
        ['title' => '测试2'],
        ['title' => '测试3'],
        ['title' => '测试4'],
        ['title' => '测试5'],
        ['title' => '测试6'],
        ['title' => '测试7'],
        ['title' => '测试8'],
        ['title' => '测试9'],
        ['title' => '测试10'],
        ['title' => '测试11'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::insert($this->types);
    }
}
