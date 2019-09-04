<?php

namespace App\Rules;

use App\Models\Record;
use App\Models\Room;
use Illuminate\Contracts\Validation\Rule;

class RoomCanMoveTo implements Rule
{
    private $message;
    private $recordId;

    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $moveToRoom = Room::where('title', $value)->first();
        if (!$moveToRoom) {
            $this->message = '房间不存在';
            return false;
        }

        $record = Record::find($this->recordId);
        if ($moveToRoom->id == $record->room_id) {
            $this->message = '不能调整至相同的房间';
            return false;
        }

        $recordTypeId = $record->type_id;
        $moveToTypeId = $moveToRoom->type_id;
        if ($recordTypeId !== $moveToTypeId) {
            $this->message = '房间类型不一致。请先将此人退房后，再重新入住新房间';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
