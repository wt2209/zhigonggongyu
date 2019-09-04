<?php

namespace App\Exceptions;

use Exception;

class ExcelInvalidBillDataException extends Exception
{
    public function __construct($message, int $code = 403)
    {
        parent::__construct($message, $code);
    }
    // 当这个异常被触发时，会调用 render 方法来输出给用户
    public function render()
    {
        return redirect()->route('bills.import-error')->withErrors(['excel' => $this->message]);
    }
}
