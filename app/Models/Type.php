<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Type extends Model
{
    const FEE_TYPE_TOTAL = 1;
    const FEE_TYPE_PART = 2;
    const FEE_TYPE_FREE = 3;
    const FEE_TYPE_NULL = 4;

    public static $feeTypeMap = [
        self::FEE_TYPE_TOTAL => '全额收取',
        self::FEE_TYPE_PART => '收取超费',
        self::FEE_TYPE_FREE => '全免',
        self::FEE_TYPE_NULL => '无',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function rooms()
    {
        return $this->hasMany('App\Models\Room');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function people()
    {
        return $this->hasManyThrough(Record::class, Room::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return substr($value, 0, 10);
    }
}
