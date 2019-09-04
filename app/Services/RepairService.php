<?php
/**
 * Created by PhpStorm.
 * User: WT
 * Date: 2018/9/10
 * Time: 15:12
 */

namespace App\Services;

use App\Models\Repair;
use App\Models\RepairItem;
use App\Models\RepairItemUsage;
use App\Models\RepairType;
use App\Models\RepairTypeUsage;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use DB;

class RepairService
{
    public function checkReviewed($id)
    {
        $repair = Repair::findOrFail($id);
        if ($repair->reviewed_at !== null) {
            abort(404);
        }
    }

    public function checkFinished($id)
    {
        $repair = Repair::findOrFail($id);
        if ($repair->finished_at !== null) {
            abort(404);
        }
    }

    public function print($id)
    {
        return Repair::where('id', $id)
            ->whereNull('printed_at')
            ->update([
                'printed_at' => now(),
            ]);
    }

    public function batchReview(array $ids)
    {
        $in = [];
        foreach ($ids as $id) {
            $in[] = (int) $id;
        }
        return Repair::whereIn('id', $in)->update([
            'review_user_id' => Admin::user()->id,
            'reviewed_at' => now(),
            'is_passed' => true,
        ]);
    }

    public function batchFinish(array $ids)
    {
        $in = [];
        foreach ($ids as $id) {
            $in[] = (int) $id;
        }
        return Repair::whereIn('id', $in)->update([
            'finished_at' => now(),
        ]);
    }

    /**
     * 修改或创建用工用料
     * @param Request $request
     * @throws \Throwable
     */
    public function updateOrCreateMaterials(Request $request)
    {
        $repair = Repair::findOrFail($request->id);
        DB::transaction(function () use ($repair, $request) {
            $repair->types()->delete();
            $repair->items()->delete();
            if ($request->has('types')) {
                $this->createTypes($repair, $request->input('types'));
            }
            if ($request->has('items')) {
                $this->createItems($repair, $request->input('items'));
            }
        });
    }

    private function createTypes(Repair $repair, $types)
    {
        $repairTypes = RepairType::pluck('price', 'id');
        $data = [];
        // 保证一个工种在一个维修项目下只能出现一次。以第一次出现的为准
        $typeIds = [];
        foreach ($types as $type) {
            // 已经存在此工种
            if (in_array($type['id'], $typeIds)) {
                continue;
            }
            $data[] = [
                'repair_id' => $repair->id,
                'repair_type_id' => $type['id'],
                'price' => $repairTypes[$type['id']] ?? 0,
                'total' => $type['total'],
                'remark' => $type['remark'],
            ];
        }
        RepairTypeUsage::insert($data);
    }

    private function createItems(Repair $repair, $items)
    {
        $repairItems = RepairItem::pluck('price', 'id');
        $data = [];
        // 保证一个材料在一个维修项目下只能出现一次。以第一次出现的为准
        $itemIds = [];
        foreach ($items as $item) {
            // 已经存在此材料
            if (in_array($item['id'], $itemIds)) {
                continue;
            }
            $itemIds[] = $item['id'];
            $data[] = [
                'repair_id' => $repair->id,
                'repair_item_id' => $item['id'],
                'price' => $repairItems[$item['id']] ?? 0,
                'total' => $item['total'],
                'remark' => $item['remark'],
            ];
        }
        RepairItemUsage::insert($data);
    }
}