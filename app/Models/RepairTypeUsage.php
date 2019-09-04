<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairTypeUsage extends Model
{
    protected $fillable = ['repair_id', 'repair_type_id', 'price', 'total', 'remark'];

    public function type()
    {
        return $this->belongsTo(RepairType::class, 'repair_type_id');
    }
}
