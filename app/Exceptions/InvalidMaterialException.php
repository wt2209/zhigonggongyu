<?php

namespace App\Exceptions;

use Exception;

class InvalidMaterialException extends Exception
{
    public function render()
    {
        abort(404, $this->message);
    }
}
