<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\BillType;
use App\Models\Type;

class BillTypeExporter extends ExcelExporter
{
    protected $fileName = '费用类型表';

    public function getExcelData()
    {
        $types = $this->getData();

        $data[] =  ['名称', '是否上交财务', '备注', '创建时间'];
        foreach ($types as $type) {
            $data[] = [
                $type['title'],
                $type['turn_in'] ? '是' : '否',
                $type['remark'],
                $type['created_at'],
            ];
        }
        return $data;
    }
}