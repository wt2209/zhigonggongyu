<?php

namespace App\Admin\Extensions\Exporters;

use App\Models\BillType;
use App\Models\RepairItemUsage;
use App\Models\RepairType;
use App\Models\RepairTypeUsage;

class RepairFinishedExporter extends ExcelExporter
{
    protected $fileName = '维修项目明细表';

    public function getExcelData()
    {
        $repairs = $this->getData();
        $data[] = [
            '地点', '维修内容', '报修人', '电话', '报修时间', '审核时间',
            '审核说明', '首次打印时间', '完工时间', '维修人', '完工说明',
        ];
        foreach ($repairs as $repair) {
            $data[] = [
                $repair['location'],
                $repair['content'],
                $repair['name'],
                $repair['phone_number'],
                $repair['created_at'],
                $repair['reviewed_at'],
                $repair['review_remark'],
                $repair['printed_at'],
                $repair['finished_at'],
                $repair['finisher'],
                $repair['finish_remark'],
            ];
        }
        return $data;
    }

    /*
     *
     * 到处带材料和工种的明细
    public function getExcelData()
    {
        $repairs = $this->getData();

        $data[] =  [
            '位置', '维修内容', '报修时间', '完工时间', '用工', '单价(元)', '用量', '小计(元)',
            '用料', '规格', '单价(元)', '用量', '小计(元)', '合计(元)'
        ];
        foreach ($repairs as $repair) {
            $types = RepairTypeUsage::with('type')->where('repair_id', $repair['id'])->get()->toArray();
            $items = RepairItemUsage::with('item')->where('repair_id', $repair['id'])->get()->toArray();
            $typeTotalCost = array_sum(array_map(function($type){
                return $type['price'] * $type['total'];
            }, $types));
            $itemTotalCost = array_sum(array_map(function($item){
                return $item['price'] * $item['total'];
            }, $items));

            $rowNumber = count($types) > count($items) ? count($types) : count($items);

            for ($i = 0; $i < $rowNumber; $i++) {
                if ($i == 0) { // 第一行
                    $basic = [
                        $repair['location'],
                        $repair['content'],
                        $repair['created_at'],
                        $repair['finished_at'],
                    ];
                    $totalCost = [$itemTotalCost + $typeTotalCost];
                } else {
                    $basic = [
                        '',
                        '',
                        '',
                        '',
                    ];
                    $totalCost = [''];
                }
                $material = [
                    $types[$i]['type']['title'] ?? '',
                    $types[$i]['price'] ?? '',
                    $types[$i]['total'] ?? '',
                    (isset($types[$i]['price']) && isset($types[$i]['total'])) ? ($types[$i]['price'] * $types[$i]['total']) : '',
                    $items[$i]['item']['name'] ?? '',
                    $items[$i]['item']['feature'] ?? '',
                    $items[$i]['price'] ?? '',
                    $items[$i]['total'] ?? '',
                    isset($items[$i]['price']) && isset($items[$i]['total']) ? $items[$i]['price'] * $items[$i]['total'] : '',
                ];

                $data[] = array_merge($basic, $material, $totalCost);
            }
        }
        return $data;
    }*/
}