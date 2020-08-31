<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\Person;
use App\Models\Record;
use App\Models\Room;
use App\Models\Type;

class RecordExporter extends ExcelExporter
{
    protected $fileName = '入住记录明细表';

    public function getExcelData()
    {
        $records = $this->getData();
        usort($records, function ($a, $b) {
            return $a['room_id'] < $b['room_id'] ? -1 : 1;
        });

        $roomIds = Record::withTrashed()->whereNotNull('to_room_id')->pluck('to_room_id');
        $idToRooms = Room::whereIn('id', $roomIds)->pluck('title', 'id');
        $data[] =  ['房间号', '楼号', '单元', '类型', '姓名', '性别', '学历', '身份证号','工号', '部门', '电话', '入住公寓时间',
            '此房间入住日', '劳动合同开始日', '劳动合同结束日', '租期开始日', '租期结束日', '状态', '调房/退房日期'];
        foreach ($records as $record) {
            $data[] = [
                $record['room']['title'],
                $record['room']['building'],
                $record['room']['unit'],
                $record['type']['title'],
                $record['person']['name'],
                $record['person']['gender'],
                Person::$educationMap[$record['person']['education']],
                "'" . $record['person']['identify'],
                $record['person']['serial'],
                $record['person']['department'],
                $record['person']['phone_number'],
                $record['person']['entered_at'],
                $record['record_at'],
                $record['person']['contract_start'],
                $record['person']['contract_end'],
                $record['type']['has_contract'] ? $record['start_at'] : '',
                $record['type']['has_contract'] ? $record['end_at'] : '',
                $record['status'] === Record::STATUS_MOVE
                    ? Record::$statusMap[$record['status']] . '到' . $idToRooms[$record['to_room_id']]
                    : Record::$statusMap[$record['status']],
                $record['deleted_at'],
            ];
        }
        return $data;
    }
}
