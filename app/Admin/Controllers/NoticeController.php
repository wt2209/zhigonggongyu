<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Person;
use App\Models\Record;
use App\Models\Repair;
use Encore\Admin\Controllers\ModelForm;

class NoticeController extends Controller
{
    private $preDate;

    public function __construct()
    {
        $this->preDate = date('Y-m-d', strtotime('25 days ago'));
    }

    public function notice()
    {
        $peopleWithManyRooms = Person::with(['records', 'records.room', 'records.type'])
            ->has('records', '>', 1)
            ->get();

        $recordsNeedRenew = Record::with(['person', 'room', 'type'])
            ->whereNotNull('start_at')
            ->whereNotNull('end_at')
            ->where('end_at', '<', now())
            ->get();

        $billsNotCharged = Bill::with('type')
            ->where('created_at', '<', $this->preDate)
            ->whereNull('payed_at')
            ->get()
            ->groupBy('location');

    /*    $people = Person::all();
        $peopleWillRetire = $people->filter(function ($person) {
            // 30天内退休
            if ($person->retired_at) {
                // 处理32位系统的问题。。。。。
                if (substr($person->retired_at, 0, 4) > 2038) {
                    return false;
                }
                return strtotime($person->retired_at) - time() < 30 * 24 * 3600;
            }
            return false;
        });*/

        return view('notices.notice', compact(
            'peopleWithManyRooms',
            'recordsNeedRenew',
            'billsNotCharged'
//            'peopleWillRetire'
        ));
    }

    public function noticeNumber()
    {
        $count = Person::with(['records', 'records.room', 'records.type'])
            ->has('records', '>', 1)
            ->count();

        $count += Record::with(['person', 'room', 'type'])
            ->whereNotNull('start_at')
            ->whereNotNull('end_at')
            ->where('end_at', '<', now())
            ->count();

        $count += Bill::with('type')
            ->where('created_at', '<', $this->preDate)
            ->whereNull('payed_at')
            ->count();

        /*$people = Person::all();
        $count += $people->where('retired_at', '<', time() + 30 * 24 * 3600)->count();*/

        return response()->json(['status' => 1, 'count' => $count]);
    }

    public function repairNumber()
    {
        $unreviewed = Repair::unreviewed()->count();
        $unprinted = Repair::unprinted()->count();

        return response()->json([
            'status' => 1,
            'unreviewed' => $unreviewed,
            'unprinted' => $unprinted,
        ]);
    }
}
