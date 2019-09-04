<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\BillType;
use App\Models\Type;

class BillExporter extends ExcelExporter
{
    protected $fileName = '费用明细表';

    public function getExcelData()
    {
        $bills = $this->getData();

        $data[] =  ['缴/退费', '房间号/位置', '姓名', '费用类型', '金额', '费用说明', '备注', '上交财务', '生成时间', '缴费日期'];
        $billTypes = BillType::pluck('title', 'id');
        foreach ($bills as $bill) {
            $data[] = [
                $bill['is_refund'] ? '退费' : '',
                $bill['location'],
                $bill['name'],
                $billTypes[$bill['bill_type_id']],
                $bill['cost'],
                $bill['explain'],
                $bill['remark'],
                $bill['turn_in'] ? '是' : '否',
                $bill['created_at'],
                $bill['payed_at'],
            ];
        }
        return $data;
    }
}