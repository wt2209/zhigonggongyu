<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillType;
use App\Models\Record;
use App\Models\Repair;
use App\Models\Room;
use App\Models\Type;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $viewData;
    protected $colors = [ '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#932AB6', '#CEFF3B', '#C37243', '#FFBF51', '#FF59A5', '#555555', '#0C63ED', '#13A4FF', '#B6750E'];

    public function index()
    {
        $this->setViewData();
        return view('home.index', $this->viewData);
    }

    public function search(Request $request)
    {
        $search = $request->keyword;
        if (!$search) {
            return redirect()->route('home.index');
        }

        $builder = Record::with(['room', 'room.type', 'toRoom', 'person'])->withTrashed();
        if (strpos($search, '-') !== false) { // 房间号
            $records = $builder->whereHas('room', function ($query) use ($search) {
                $query->where('title', $search);
            })->get();
        } else { // 电话或姓名
            $records = $builder->whereHas('person', function ($query) use ($search) {
                $field = is_numeric($search) ? 'phone_number' : 'name';
                $query->where($field, 'like', "%{$search}%");
            })->get();
        }
        $records = $records->groupBy('status');

        return view('home.search', compact('records'));
    }

    protected function setViewData()
    {
        $types = $this->getTypes();
        $currentDayBillsStatistics = $this->getCurrentDayBillsStatistics();
        $currentMonthCosts = Bill::withoutRefund()->payAtYear(date('Y'))->payAtMonth(date('m'))->sum('cost');
        // 维修
        $currentMonthRepairs = Repair::finishedCurrentMonth()->get();
        //30天内当日完工率
        $lastDate = date('Y-m-d', strtotime('-30 days'));
        $repairQuery = Repair::where('is_passed', true)->where('finished_at', '>=', $lastDate);
        $finishedCount = $repairQuery->count();
        $dayFinishedCount = $repairQuery->whereRaw('date(finished_at) = date(printed_at)')->count();
        $finishedRate = $finishedCount === 0 ? 0 : round($dayFinishedCount / $finishedCount * 100, 2);

        $repairing = Repair::unfinished()->get();

        $this->viewData = compact(
            'types',
            'currentMonthCosts',
            'currentDayBillsStatistics',
            'currentMonthRepairs',
            'repairing',
            'finishedRate'
        );
    }

    protected function getTypes()
    {
        // 类型
        $types = Type::withCount([
            'rooms',
            'records as people_count',
            'rooms as rooms_used_count' => function ($query) {
                $query->has('records');
            }
        ])->get();
        return $types;
    }

    protected function getCurrentDayBillsStatistics()
    {
        // 本日费用
        $bills = Bill::withoutRefund()->whereDate('payed_at', date('Y-m-d'))->get();
        // 格式： ['bill_type_id' => 'total_cost']
        $statistics = $bills->groupBy('bill_type_id')->map(function ($group) {
            return $group->sum('cost');
        });

        $billTypes = BillType::pluck('title', 'id')->toArray();

        $billsStatistics = [];
        // 格式： ['bill_type_title' => 'total_cost']
        foreach ($statistics as $typeId => $cost) {
            $billsStatistics[$billTypes[$typeId]] = $cost;
        }
        asort($billsStatistics, SORT_NUMERIC);
        return $billsStatistics;
    }
}
