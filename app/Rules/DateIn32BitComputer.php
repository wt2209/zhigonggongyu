<?php

namespace App\Rules;

use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;

class DateIn32BitComputer implements Rule
{
    protected $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        if (strtotime($value) !== false
            || $value === Person::CONTRACT_RETIRE_END
            || $value === Person::CONTRACT_DEFAULT_END
        ) {
            return true;
        }
        $this->message = '必须是一个日期格式，如：2018-9-3';
        return false;
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
