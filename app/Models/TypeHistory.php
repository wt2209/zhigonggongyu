<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeHistory extends Model
{
    protected $fillable = ['room_id', 'from_type_id', 'to_type_id', 'from_person_number', 'to_person_number'];

    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }

    public function fromType()
    {
        return $this->belongsTo('App\Models\Type', 'from_type_id');
    }

    public function toType()
    {
        return $this->belongsTo('App\Models\Type', 'to_type_id');
    }
}
