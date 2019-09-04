<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function records()
    {
        return $this->hasMany('App\Models\Record');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function people()
    {
        return $this->belongsToMany('App\Models\Person', 'records');
    }

    public function typeHistories()
    {
        return $this->hasMany('App\Models\TypeHistory');
    }
}
