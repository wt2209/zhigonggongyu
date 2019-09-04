<?php

namespace App\Admin\Extensions\Exporters;

class RepairItemExporter extends ExcelExporter
{
    protected $fileName = '维修材料明细表';

    public function getExcelData()
    {
        $items = $this->getData();

        $data[] =  ['名称', '规格', '单价', '单位', '创建时间'];
        foreach ($items as $item) {
            $data[] = [
                $item['name'],
                $item['feature'],
                $item['price'],
                $item['unit'],
                $item['created_at'],
            ];
        }
        return $data;
    }
}