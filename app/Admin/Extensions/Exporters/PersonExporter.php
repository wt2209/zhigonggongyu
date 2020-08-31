<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\Person;
use App\Models\Record;
use App\Models\Type;

class PersonExporter extends ExcelExporter
{
    protected $fileName = '人员明细表';

    public function getExcelData()
    {
        $people = $this->getData();

        $data[] =  ['姓名', '性别', '身份证号', '工号', '部门', '学历', '电话',
            '劳动合同起始日', '劳动合同结束日', '居住过的房间', '居住过的房间数', '当前状态'];
        // 在住的人的id
        $livingPersonIds = Record::where('status', 0)->pluck('person_id')->toArray();
        foreach ($people as $person) {
            $roomsNumber = count($person['rooms']);
            $data[] = [
                $person['name'],
                $person['gender'],
                "'" . $person['identify'],
                $person['serial'],
                $person['department'],
                Person::$educationMap[$person['education']],
                $person['phone_number'],
                $person['contract_start'],
                $person['contract_end'],
                $person['contract_end'],
                $roomsNumber,
                in_array($person['id'], $livingPersonIds) ? '在住' : '已退房',
            ];
        }
        return $data;
    }
}