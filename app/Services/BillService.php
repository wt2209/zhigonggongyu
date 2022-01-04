<?php
namespace App\Services;


use App\Models\BillType;
use App\Models\Bill;
use Encore\Admin\Facades\Admin;

class BillService
{
    public function batchStore($items)
    {
        $billTypes = BillType::all()->toArray();
        $typeOptions['ids'] = array_column($billTypes, 'id', 'title');
        $typeOptions['turn_ins'] = array_column($billTypes, 'turn_in', 'title');

        $inputUserId = Admin::user()->id;
        $now = now();
        $pay = request()->has('pay') ? request()->input('pay') : null;

        $items = array_map(function ($item) use ($typeOptions, $inputUserId, $now, $pay) {
            $item['bill_type_id'] = $typeOptions['ids'][$item['bill_type']];
            $item['turn_in'] = $typeOptions['turn_ins'][$item['bill_type']];
            $item['is_refund'] = $item['cost'] < 0;
            $item['created_at'] = $item['updated_at'] = $now;

            // 在退费，或者指定“录入时缴费”时，存储的同时缴费
            if ($item['is_refund'] === true || $pay === 'on') {
                $item['payed_at'] = $item['payed_at'] ?? $now;
                $item['pay_user_id'] = $inputUserId;
            } else if ($item['payed_at']) {
                $item['pay_user_id'] = $inputUserId;
            } else {
                $item['payed_at'] = $item['pay_user_id'] = null;
            }
            unset($item['bill_type']);
            return $item;
        }, $items);

        return Bill::insert($items);
    }

    public function getUnpayedGroupedBills($keyword, $field, $groupBy = 'location')
    {
        return $this->getUnpayedBills($keyword, $field)->groupBy($groupBy);
    }

    public function getUnpayedBills($keyword, $field)
    {
        if (!$keyword) {
            return collect();
        }
        return Bill::unpayed()->where($field, $keyword)->get();
    }

    /**
     * 统计。返回格式： ['bill_type_id' => 'total_cost', ...]
     * @param $options
     * @return \Illuminate\Support\Collection
     */
    public function getStatisticsByOptions($options)
    {
        if ($this->optionsIsEmpty($options)) {
            return collect();
        }
        $bills = $this->getBillsByOptions($options);
        return $bills->groupBy('bill_type_id')->map(function ($group) {
            return $group->sum('cost');
        });
    }

    public function charge($ids, $payedAt, $chargeMode)
    {
        $data = [
            'payed_at' => strtotime($payedAt) !== false ? $payedAt : now(),
            'charge_mode' => $chargeMode ?: '',
            'pay_user_id' => Admin::user()->id,
        ];
        return Bill::unpayed()->whereIn('id', $ids)->update($data);
    }

    private function getBillsByOptions($options)
    {
        $builder = Bill::payed();
        // 缴费、退费：只有在指定查找退费时，才查找退费，否则只查找缴费
        if (isset($options['is_refund']) && $options['is_refund'] === 'true') {
            $builder->onlyRefund();
        } else {
            $builder->withoutRefund();
        }
        // years  0代表查找全部
        if (isset($options['years']) && $this->canBuildIn($options['years'])) {
            $builder->payAtYears($options['years']);
        }
        // months
        if (isset($options['months']) && $this->canBuildIn($options['months'])) {
            $builder->payAtMonths($options['months']);
        }
        //days
        if (isset($options['days']) && $this->canBuildIn($options['days'])) {
            $builder->payAtDays($options['days']);
        }
        //types
        if (isset($options['type_ids']) && $this->canBuildIn($options['type_ids'])) {
            $builder->types($options['type_ids']);
        }
        // location
        if (isset($options['location']) && !empty($options['location'])) {
            $builder->location($options['location']);
        }
        // 姓名
        if (isset($options['name']) && !empty($options['name'])) {
            $builder->name($options['name']);
        }
        // turn_in
        if (isset($options['turn_in'])) {
            if ($options['turn_in'] === 'true') {
                $builder->turnIn();
            }
            if ($options['turn_in'] === 'false') {
                $builder->notTurnIn();
            }
        }
        if (isset($options['charge_mode']) && $options['charge_mode']) {
            $builder->where('charge_mode', $options['charge_mode']);
        }
        return $builder->get();
    }

    private function canBuildIn($param)
    {
        return is_array($param) && !in_array(0, $param);
    }

    private function optionsIsEmpty($options)
    {
        return !isset($options['years'])
            && !isset($options['months'])
            && !isset($options['days'])
            && !isset($options['type_ids']);
    }
}