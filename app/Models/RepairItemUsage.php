<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairItemUsage extends Model
{
    protected $fillable = ['repair_id', 'repair_item_id', 'price', 'total', 'remark'];

    public function item()
    {
        return $this->belongsTo(RepairItem::class, 'repair_item_id');
    }
}
