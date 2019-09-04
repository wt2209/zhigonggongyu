<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillType extends Model
{
    protected $fillable = ['title', 'remark'];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
