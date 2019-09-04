<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Bill extends Model
{
    protected $dates = ['created_at', 'payed_at', 'updated_at'];

    protected $fillable = ['location', 'name', 'bill_type_id', 'input_user_id', 'cost', 'explain', 'is_refund', 'remark', 'payed_at'];

    public function type()
    {
        return $this->belongsTo(BillType::class, 'bill_type_id');
    }

    public function scopeCanEdit($query, $id = null)
    {
        $builder = $query->whereNull('payed_at');
        if ($id) {
            $builder->where('id', $id);
        }
        return $builder;
    }

    public function scopeOnlyRefund($query)
    {
        return $query->where('is_refund', true);
    }

    public function scopeWithoutRefund($query)
    {
        return $query->where('is_refund', false);
    }

    public function scopePayed($query)
    {
        return $query->whereNotNull('payed_at');
    }

    public function scopeUnpayed($query)
    {
        return $query->whereNull('payed_at');
    }

    public function scopeTurnIn($query)
    {
        return $query->where('turn_in', true);
    }

    public function scopeNotTurnIn($query)
    {
        return $query->where('turn_in', false);
    }

    public function scopePayAtYear($query, $year)
    {
        return $query->whereYear('payed_at', $year);
    }

    public function scopePayAtMonth($query, $month)
    {
        return $query->whereMonth('payed_at', $month);
    }

    public function scopePayAtDay($query, $day)
    {
        return $query->whereDay('payed_at', $day);
    }

    public function scopePayAtYears($query, $years)
    {
        return $query->whereIn(DB::raw('YEAR(payed_at)'), $years);
    }

    public function scopePayAtMonths($query, $months)
    {
        return $query->whereIn(DB::raw('MONTH(payed_at)'), $months);
    }

    public function scopePayAtDays($query, $days)
    {
        return $query->whereIn(DB::raw('DAY(payed_at)'), $days);
    }

    public function scopeLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function scopeTypes($query, $typeIds)
    {
        if (is_array($typeIds)) {
            return $query->whereIn('bill_type_id', $typeIds);
        }
        return $query->where('bill_type_id', $typeIds);
    }
}
