<?php

namespace App\Rules;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RecordCanRenew implements Rule
{

    private $id;
    private $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $record = Record::find($this->id);
        $endAt = Carbon::createFromFormat("Y-m-d", $record->end_at);
        $newEndAt = Carbon::createFromFormat('Y-m-d', $value);
        if ($newEndAt->lte($endAt)) {
            $this->message = '新租期结束日不能早于原租期结束日';
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
