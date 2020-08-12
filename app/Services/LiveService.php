<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Record;
use App\Models\Renewal;
use Illuminate\Http\Request;
use Cache;
use App\Models\Type;
use App\Models\Room;
use DB;

class LiveService
{
    /**
     * 根据条件查找房间
     *
     * @param Request $request
     * @return Room[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRooms(Request $request)
    {
        $building = $request->input('building');
        $unit = $request->input('unit');

        // 电话或姓名
        $keyword = $request->input('keyword');

        // 租期搜索
        $status = $request->input('status');
        $start = $request->input('start');
        $end = $request->input('end');
        $dateTypeId = $request->input('date_type_id');

        if ($building && $unit) {
            $rooms = $this->getRoomsByUnit($building, $unit);
        } else if ($keyword) {
            $rooms = $this->getRoomsByKeyword($keyword);
        } else if ($dateTypeId && $status && $start && $end) {
            $rooms = $this->getRoomsByDate($dateTypeId, $status, $start, $end);
        } else {
            $rooms = collect([]);
        }
        return $rooms;
    }

    /**
     * 存储入住信息
     * @param array $inputs
     * @throws \Throwable
     */
    public function store(array $inputs)
    {
        DB::transaction(function () use ($inputs) {
            $room = Room::findOrFail($inputs['room_id']);
            if (isset($inputs['person']['identify']) && $inputs['person']['identify']) {
                $person = Person::updateOrCreate(
                    ['identify' => $inputs['person']['identify']],
                    $inputs['person']
                );
            } else {
                $person = Person::create($inputs['person']);
            }

            $record = new Record;
            $record->room_id = $room->id;
            $record->person_id = $person->id;
            $record->type_id = $inputs['type_id'];
            $record->record_at = now();
            $record->status = Record::STATUS_NULL;
            if ($room->type->has_contract) {
                $record->start_at = $inputs['start_at'];
                $record->end_at = $inputs['end_at'];
            }
            $record->save();
        }, 3);
    }

    /**
     * 更新入住信息
     * @param $recordId
     * @param $inputs
     * @throws \Throwable
     */
    public function update($recordId, $inputs)
    {
        DB::transaction(function () use ($recordId, $inputs) {
            $record = Record::findOrFail($recordId);
            $record->record_at = $inputs['record_at'];
            $record->type_id = $inputs['type_id'];
            if ($record->type->has_contract) {
                $record->start_at = $inputs['start_at'];
                $record->end_at = $inputs['end_at'];
            }
            $record->save();

            $person = $record->person;
            $person->update($inputs['person']);
        }, 3);
    }

    /**
     * 调房
     * @param $recordId
     * @param $moveToRoom
     * @throws \Throwable
     */
    public function move($recordId, $moveToRoom)
    {
        DB::transaction(function () use ($recordId, $moveToRoom) {
            $now = now();
            $toRoomId = Room::where('title', $moveToRoom)->value('id');

            $record = Record::find($recordId);
            $record->status = Record::STATUS_MOVE;
            $record->deleted_at = $now;
            $record->to_room_id = $toRoomId;
            $record->save();

            // 创建新的记录
            Record::create([
                'person_id' => $record->person_id,
                'room_id' => $toRoomId,
                'type_id' => $record->type_id,
                'record_at' => $now,
                'start_at' => $record->start_at ?: null,
                'end_at' => $record->end_at ?: null,
                'status' => Record::STATUS_NULL,
            ]);
        }, 3);
    }

    /**
     * 续签
     * @param $recordId
     * @param $request
     * @throws \Throwable
     */
    public function renew($recordId, $request)
    {
        DB::transaction(function () use ($recordId, $request) {
            $newEndAt = $request->input('new_end_at');
            $newContractEnd = $request->input('new_contract_end');

            $record = Record::findOrFail($recordId);

            Renewal::create([
                'record_id' => $recordId,
                'end_at' => $record->end_at,
                'new_end_at' => $newEndAt,
            ]);

            $record->end_at = $newEndAt;
            $record->save();

            $person = $record->person;
            $person->contract_end = $newContractEnd;
            $person->save();
        }, 3);
    }

    /**
     * 退房
     *
     * @param $recordId
     * @return bool
     */
    public function quit($recordId)
    {
        $record = Record::find($recordId);
        $record->status = Record::STATUS_QUIT;
        $record->deleted_at = now();
        return (bool) $record->save();
    }

    /**
     * 获取多级房间结构（用于选择楼号）
     * @return array
     */
    public function getRoomStructure()
    {
        if (Cache::has('room-structure')) {
            return Cache::get('room-structure');
        }
        $structure = $this->createRoomStructure();
        Cache::forever('room-structure', $structure);
        return $structure;
    }

    /**
     * 创建多级房间结构
     * @return array
     */
    private function createRoomStructure()
    {
        $rooms = Room::select(['building', 'unit'])
            ->groupBy('building', 'unit')
            ->get()
            ->toArray();

        $data = [];
        foreach ($rooms as $room) {
            $building = $room['building'];
            $unit = $room['unit'];
            if (isset($data[$building])) {
                array_push($data[$building], $unit);
            } else {
                $data[$building] = [$unit];
            }
        }
        // 各楼的单元排序
        foreach ($data as $building => $units) {
            sort($data[$building], SORT_NUMERIC);
        }
        // 楼号排序
        uksort($data, function ($a, $b) {
            $a = str_replace(['红', '高'], [2, 3], $a);
            $b = str_replace(['红', '高'], [2, 3], $b);
            return intval($a) > intval($b);
        });
        return $data;
    }

    /**
     * 根据楼号、单元号获取房间
     *
     * @param $building
     * @param $unit
     * @return Room[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getRoomsByUnit($building, $unit)
    {
        // 会自动过滤已经被softDelete的records
        return Room::with(['type', 'records', 'records.person', 'records.type'])
            ->where('building', $building)
            ->where('unit', $unit)
            ->get();
    }

    /**
     * 根据关键字（搜索）获取房间
     *
     * @param $keyword
     * @return Room[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getRoomsByKeyword($keyword)
    {
        if (strpos($keyword, '-') !== false) { // 房间号
            return Room::with(['type', 'records', 'records.person', 'records.type'])
                ->where('title', 'like', "%{$keyword}%")
                ->get();
        }
        // 处理电话和姓名
        $field = is_numeric($keyword) ? 'phone_number' : 'name';
        return Room::with(['type', 'records', 'records.person', 'records.type'])
            ->whereHas('records.person', function ($query) use ($field, $keyword) {
                $query->where($field, 'like', "%{$keyword}%");
            })
            ->get();
    }

    /**
     * 根据租期搜索房间
     *
     * @param $dateTypeId
     * @param $status
     * @param $start
     * @param $end
     * @return Room[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getRoomsByDate($dateTypeId, $status, $start, $end)
    {
        $between = [$start, $end];
        $field = $status === 'start' ? 'start_at' : 'end_at';

        return Room::with(['type', 'records', 'records.person', 'records.type'])
            ->where('type_id', $dateTypeId)
            ->whereHas('records', function ($query) use ($field, $between) {
                $query->whereBetween($field, $between);
            })
            ->get();
    }
}
