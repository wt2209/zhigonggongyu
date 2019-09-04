<?php

namespace App\Rules;

use App\Models\Record;
use App\Models\Room;
use Illuminate\Contracts\Validation\Rule;

class RoomEmpty implements Rule
{

    private $roomId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($roomId)
    {
        $this->roomId = $roomId;
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
        $original = Room::where('id', $this->roomId)->value('type_id');
        if ($original == $value) {
            return true;
        }
        return !Record::where('room_id', $this->roomId)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '只有空房间才能更改类型，请先将本房间人员调房或退房';
    }
}
