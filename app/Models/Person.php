<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    //默认入住公寓日期
    const DEFAULT_ENTER_DATE = '1000-01-01';

    //默认劳动合同日期
    const CONTRACT_DEFAULT_START = '1000-01-01';
    const CONTRACT_DEFAULT_END = '9999-12-31';
    const CONTRACT_RETIRE_END = '3000-01-01'; // 无固定期


    const EDUCATION_COLLEGE = 1;
    const EDUCATION_BACHELOR = 2;
    const EDUCATION_MASTER = 3;
    const EDUCATION_ELSE = 4;
    const EDUCATION_UNKNOWN = 5;

    public static $educationMap = [
        self::EDUCATION_COLLEGE => '专科',
        self::EDUCATION_BACHELOR => '本科',
        self::EDUCATION_MASTER => '硕士',
        self::EDUCATION_ELSE => '其他',
        self::EDUCATION_UNKNOWN => '未知',
    ];

    protected $fillable = ['name', 'short_name', 'identify', 'gender',
        'department', 'entered_at', 'contract_start', 'contract_end',
        'education', 'phone_number', 'remark', 'spouse_name'];

    public function records()
    {
        return $this->hasMany('App\Models\Record');
    }

    public function rooms()
    {
        return $this->belongsToMany('App\Models\Room','records');
    }

    public function getContractStartAttribute($value)
    {
        return $value == self::CONTRACT_DEFAULT_START ? '' : $value;
    }

    public function getContractEndAttribute($value)
    {
        switch ($value) {
            case self::CONTRACT_DEFAULT_END:
                return '';
            case self::CONTRACT_RETIRE_END:
                return '无固定期';
            default:
                return $value;
        }
    }

    public function getEnteredAtAttribute($value)
    {
        return $value == self::DEFAULT_ENTER_DATE ? '' : $value;
    }

    public function setEnteredAtAttribute($value)
    {
        if (!$value) {
            $value = self::DEFAULT_ENTER_DATE;
        }
        $this->attributes['entered_at'] = $value;
    }

    public function getRetiredAtAttribute()
    {
        if ($this->identify === null) {
            return '';
        }
        if (strlen($this->identify) === 18) { // 18位新版身份证号
            $year = substr($this->identify, 6, 4);
            $month = substr($this->identify, 10, 2);
            $day = substr($this->identify, 12, 2);
        } else {
            $year = '19' . substr($this->identify, 6, 2);
            $month = substr($this->identify, 8, 2);
            $day = substr($this->identify, 10, 2);
        }
        $add = $this->gender === '男' ? 60 : 50;
        $retireYear = $year + $add;
        return $retireYear . '-' . $month . '-' . $day;
    }

    public function setContractStartAttribute($value)
    {
        if (!$value) {
            $value = self::CONTRACT_DEFAULT_START;
        }
        $this->attributes['contract_start'] = $value;
    }

    public function setContractEndAttribute($value)
    {
        if (!$value) {
            $value = self::CONTRACT_DEFAULT_END;
        }
        $this->attributes['contract_end'] = $value;
    }
}
