<?php

use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    protected $types;
    public function __construct()
    {
        $this->types = \App\Models\Type::pluck('id')->toArray();
    }

    /**
     * @var array
     */
    protected $rooms = [
        '多层' => [
            '1' => [1, 2],
            '2' => [1, 2, 3, 4],
            '3' => [1, 2, 3],
            '4' => [1, 2, 3],
            '5' => [1, 2, 3, 4],
            '6' => [1, 2, 3, 4],
            '7' => [1, 2, 3, 4, 5],
            '8' => [1, 2, 3, 4],
            '9' => [1, 2, 3, 4],
            '10' => [1, 2, 3, 4],
            '11' => [1, 2, 3, 4],
            '12' => [1, 2, 3, 4],
            '13' => [1, 2, 3, 4],
            '14' => [1, 2, 3],
            '红1' => [1, 2],
            '红2' => [1, 2, 3],
            '红3' => [1, 2, 3, 4],
        ],
        '高层' => [
            '高1', '高2','高3','高4',
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertLowerRooms();
        $this->insertHigherRooms();
    }

    private function insertLowerRooms()
    {
        $data = [];
        foreach ($this->rooms['多层'] as $building => $units) {
            foreach ($units as $unit) {
                for ($i = 1; $i <= 6; $i++) {
                    $data[] = [
                        'title' => $building . '-' . $unit . '-' . $i .'01',
                        'building' => $building . '#',
                        'type_id' => $this->types[random_int(0, count($this->types) - 1)],
                        'unit' => $unit . '单元',
                        'remark' => '',
                    ];
                    $data[] = [
                        'title' => $building . '-' . $unit . '-' . $i .'02',
                        'type_id' => $this->types[random_int(0, count($this->types) - 1)],
                        'building' => $building . '#',
                        'unit' => $unit . '单元',
                        'remark' => '',
                    ];
                }
            }
        }
        \App\Models\Room::insert($data);
    }

    private function insertHigherRooms()
    {
        $data = [];
        foreach ($this->rooms['高层'] as $building) {
            $start = $building == '高1' ? 3 : 1;
            $end = $building == '高1' ? 17 : 20;
            $notExistFloors = [13, 18];

            for ($i = $start; $i <= $end; $i++) {
                if (in_array($i, $notExistFloors)) {
                    continue;
                }
                if ($building == '高1') {
                    if ($i >= 3 && $i <= 7) {
                        $unit = '3-7层';
                    }
                    if ($i >= 8 && $i <= 12) {
                        $unit = '8-12层';
                    }
                    if ($i >= 14 && $i <= 17) {
                        $unit = '14-17层';
                    }
                } else {
                    if ($i >= 1 && $i <= 5) {
                        $unit = '1-5层';
                    }
                    if ($i >= 6 && $i <= 10) {
                        $unit = '6-10层';
                    }
                    if ($i >= 11 && $i <= 16) {
                        $unit = '11-16层';
                    }
                    if ($i >= 17 && $i <= 20) {
                        $unit = '17-20层';
                    }
                }
                for ($j = 1; $j <=4; $j++) {
                    $data[] = [
                        'title' => $building . '-' . $i . '0' . $j,
                        'building' => $building . '#',
                        'type_id' => $this->types[random_int(0, count($this->types) - 1)],
                        'unit' => $unit,
                        'remark' => '',
                    ];
                }
            }
        }

        \App\Models\Room::insert($data);
    }
}
