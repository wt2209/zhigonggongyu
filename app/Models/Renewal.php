<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    protected $fillable = ['record_id', 'end_at', 'new_end_at'];

    public function record()
    {
        return $this->belongsTo(Record::class)->withTrashed();
    }

    public function getCreatedAtAttribute($value)
    {
        return substr($value, 0, 10);
    }
}
