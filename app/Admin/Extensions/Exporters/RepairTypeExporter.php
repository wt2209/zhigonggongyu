<?php

namespace App\Admin\Extensions\Exporters;

class RepairTypeExporter extends ExcelExporter
{
    protected $fileName = '维修工种明细表';

    public function getExcelData()
    {
        $items = $this->getData();

        $data[] =  ['工种', '单价', '创建时间'];
        foreach ($items as $item) {
            $data[] = [
                $item['title'],
                $item['price'],
                $item['created_at'],
            ];
        }
        return $data;
    }
}