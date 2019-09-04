<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    const ITEM_UNREVIEWED = 1;
    const ITEM_UNPRINTED = 2;
    const ITEM_UNFINISHED = 3;
    const ITEM_FINISHED = 4;

    public static $steps = [
        self::ITEM_UNREVIEWED => [
            'label'=>'未审核',
            'name' => 'repairs.unreviewed',
        ],
        self::ITEM_UNPRINTED => [
            'label' => '未打印',
            'name' => 'repairs.unprinted',
        ],
        self::ITEM_UNFINISHED => [
            'label' => '正在维修',
            'name' => 'repairs.unfinished',
        ],
        self::ITEM_FINISHED => [
            'label' => '已完工',
            'name' => 'repairs.finished',
        ],
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'reviewed_at',
        'printed_at',
        'finished_at',
    ];

    public function inputer()
    {
        return $this->belongsTo(config('admin.database.users_model'), 'input_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(config('admin.database.users_model'), 'review_user_id');
    }

    public function types()
    {
        return $this->hasMany(RepairTypeUsage::class, 'repair_id');
    }

    public function items(){
        return $this->hasMany(RepairItemUsage::class, 'repair_id');
    }

    public function scopeFinishedYear($query, $year)
    {
        return $query->whereYear('finished_at', $year);
    }

    public function scopeFinishedMonth($query, $month)
    {
        return $query->whereMonth('finished_at', $month);
    }

    public function scopeFinishedDay($query, $day)
    {
        return $query->whereDay('finished_at', $day);
    }

    public function scopeUnreviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    public function scopeUnprinted($query)
    {
        return $query->whereNotNull('reviewed_at')
            ->where('is_passed', true)
            ->whereNull('printed_at');
    }

    public function scopeUnfinished($query)
    {
        return $query->whereNotNull('printed_at')
            ->whereNull('finished_at')
            ->whereNotNull('reviewed_at')
            ->where('is_passed', true);
    }

    public function scopeFinished($query)
    {
        return $query->whereNotNull('finished_at')
            ->whereNotNull('printed_at')
            ->whereNotNull('reviewed_at')
            ->where('is_passed', true);
    }

    public function scopeUnpassed($query)
    {
        return $query->whereNotNull('reviewed_at')
            ->where('is_passed', false);
    }

    public function scopeFinishedCurrentMonth($query)
    {
        return $query->finished()
            ->whereYear('finished_at', date('Y'))
            ->whereMonth('finished_at', date('m'));
    }

    public function getCreatedAtAttribute($value)
    {
        return substr($value, 0, 10);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('orderByIdDesc', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }
}
