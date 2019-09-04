<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\Person;
use App\Models\Record;
use App\Models\Room;
use App\Models\Type;

class RenewalExporter extends ExcelExporter
{
    protected $fileName = '续签记录表';

    public function getExcelData()
    {
        $renewals = $this->getData();

        $data[] =  ['房间号', '姓名', '身份证号', '原租期', '现租期', '续签日期'];
        foreach ($renewals as $renewal) {
            $data[] = [
                $renewal['record']['room']['title'],
                $renewal['record']['person']['name'],
                $renewal['record']['person']['identify'] . ' ',
                $renewal['record']['start_at'] . '—' . $renewal['end_at'],
                $renewal['record']['start_at'] . '—' . $renewal['new_end_at'],
                $renewal['created_at'],
            ];
        }
        return $data;
    }
}