<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\Type;

class TypeExporter extends ExcelExporter
{
    protected $fileName = '房间类型明细表';

    public function getExcelData()
    {
        $types = $this->getData();

        $data[] =  ['名称', '收费方式', '存在期限', '收取租金', '备注', '创建时间'];
        foreach ($types as $type) {
            $data[] = [
                $type['title'],
                Type::$feeTypeMap[$type['fee_type']],
                $type['has_contract'] ? '是' : '否',
                ($type['has_contract'] && $type['has_rent_fee']) ? '是' : '',
                $type['remark'],
                $type['created_at'],
            ];
        }
        return $data;
    }
}