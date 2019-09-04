<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use SoftDeletes;

    const DEFAULT_RECORD_DATE = '1000-01-01';
    const DEFAULT_START_DATE = '1000-01-01';
    const DEFAULT_END_DATE = '9999-12-31';

    const STATUS_NULL = 0;
    const STATUS_MOVE = 1;
    const STATUS_QUIT = 2;

    public static $statusMap = [
        self::STATUS_NULL => '在住',
        self::STATUS_MOVE => '调房',
        self::STATUS_QUIT => '已退房',
    ];

    protected $fillable = ['person_id', 'room_id', 'type_id', 'record_at', 'start_at', 'end_at', 'status'];

    protected $dates = [
        'record_at',
        'start_at',
        'end_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }

    public function toRoom()
    {
        return $this->belongsTo('App\Models\Room', 'to_room_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }

    public function getRecordAtAttribute($value)
    {
        return substr($value, 0, 10) == self::DEFAULT_RECORD_DATE
            ? ''
            : substr($value, 0, 10);
    }

    public function getDeletedAtAttribute($value)
    {
        return substr($value, 0, 10);
    }

    public function getStartAtAttribute($value)
    {
        return substr($value, 0, 10) == self::DEFAULT_START_DATE
            ? ''
            : substr($value, 0, 10);
    }

    public function getEndAtAttribute($value)
    {
        return substr($value, 0, 10) == self::DEFAULT_END_DATE
            ? ''
            : substr($value, 0, 10);
    }

    public function setRecordAtAttribute($value)
    {
        if (!$value) {
            $value = self::DEFAULT_RECORD_DATE;
        }
        $this->attributes['record_at'] = $value;
    }
}
