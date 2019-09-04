<?php
namespace App\Observers;

use App\Models\Room;
use App\Models\TypeHistory;
use Cache;

class TypeObserver
{
    public function updated()
    {
        $this->forgetCache();
    }

    public function deleted()
    {
        $this->forgetCache();
    }

    public function created()
    {
        $this->forgetCache();
    }

    private function forgetCache()
    {
        if (Cache::has('room-structure')) {
            Cache::forget('room-structure');
        }
    }
}