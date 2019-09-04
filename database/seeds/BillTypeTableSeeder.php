<?php

use Illuminate\Database\Seeder;

class BillTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['title'=>'租赁房租', 'turn_in'=>true],
            ['title'=>'租赁物业费', 'turn_in'=>false],
            ['title'=>'租赁电梯费', 'turn_in'=>false],
            ['title'=>'租赁电费', 'turn_in'=>true],
            ['title'=>'租赁水费', 'turn_in'=>true],
            ['title'=>'租赁滞纳金', 'turn_in'=>true],
            ['title'=>'租赁采暖费', 'turn_in'=>true],
            ['title'=>'租赁押金', 'turn_in'=>false],
            ['title'=>'租赁剩余燃气', 'turn_in'=>true],
            ['title'=>'租赁剩余有线', 'turn_in'=>true],
            ['title'=>'租赁赔偿', 'turn_in'=>true],
            ['title'=>'单身床位费', 'turn_in'=>true],
            ['title'=>'单身电费', 'turn_in'=>true],
            ['title'=>'单身水费', 'turn_in'=>true],
            ['title'=>'单身押金', 'turn_in'=>false],
            ['title'=>'单身剩余燃气', 'turn_in'=>true],
            ['title'=>'大学生超费', 'turn_in'=>true],
            ['title'=>'派遣工超费', 'turn_in'=>true],
            ['title'=>'派遣工押金', 'turn_in'=>false],
            ['title'=>'超市电费', 'turn_in'=>true],
            ['title'=>'超市物业费', 'turn_in'=>true],
            ['title'=>'超市滞纳金', 'turn_in'=>true],
            ['title'=>'饮水机电费', 'turn_in'=>true],
            ['title'=>'饮水机水费', 'turn_in'=>true],
            ['title'=>'天网电费', 'turn_in'=>true],
            ['title'=>'武船押金', 'turn_in'=>false],
            ['title'=>'武船超费', 'turn_in'=>true],
            ['title'=>'领导超费', 'turn_in'=>true],
            ['title'=>'中层超费', 'turn_in'=>true],
        ];
        \App\Models\BillType::insert($types);
    }
}
