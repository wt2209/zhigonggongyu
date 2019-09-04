<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\Type;

class RoomExporter extends ExcelExporter
{
    protected $fileName = '房间明细表';

    public function getExcelData()
    {
        $rooms = $this->getData();

        $data[] =  ['房间号', '所属类型', '房间状态', '定员人数', '当前人数', '收费方式', '备注'];
        foreach ($rooms as $room) {
            $peopleNumber = count($room['records']);
            $data[] = [
                $room['title'],
                $room['type']['title'],
                $peopleNumber == 0 ? '空房间' : '在用',
                $room['person_number'],
                $peopleNumber,
                Type::$feeTypeMap[$room['type']['fee_type']],
                $room['remark'],
            ];
        }
        return $data;
    }
}