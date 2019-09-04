<?php

namespace App\Admin\Extensions\Exporters;


class TypeHistoryExporter extends ExcelExporter
{
    protected $fileName = '房间类型变动表';

    public function getExcelData()
    {
        $histories = $this->getData();
        $data[] =  ['房间号', '原类型', '新类型', '原人数', '新人数', '变动时间'];
        foreach ($histories as $history) {
            $data[] = [
                $history['room']['title'],
                $history['from_type']['title'],
                $history['to_type']['title'],
                $history['from_person_number'],
                $history['to_person_number'],
                $history['created_at'],
            ];
        }
        return $data;
    }
}