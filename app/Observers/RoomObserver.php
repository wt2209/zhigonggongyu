<?php
namespace App\Observers;

use App\Models\Room;
use App\Models\TypeHistory;
use Cache;

class RoomObserver
{
    public function updated(Room $room)
    {
        $this->forgetRoomStructure();
        
        $original = $room->getOriginal();
        $current = $room->toArray();
        if ($this->shouldCreateTypeHistory($original, $current)) {
            TypeHistory::create([
                'room_id' => $current['id'],
                'from_type_id' => $original['type_id'],
                'to_type_id' => $current['type_id'],
                'from_person_number' => $original['person_number'],
                'to_person_number' => $current['person_number'],
            ]);
        }
    }

    private function shouldCreateTypeHistory($original, $current)
    {
        return $original['type_id'] != $current['type_id']
            || $original['person_number'] != $current['person_number'];
    }

    private function forgetRoomStructure()
    {
        if (Cache::has('room-structure')) {
            Cache::forget('room-structure');
        }
    }
}