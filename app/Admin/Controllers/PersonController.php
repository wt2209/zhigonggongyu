<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Exporters\PersonExporter;
use App\Models\Person;
use App\Models\Record;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('人员');
            $content->description('所有人员，包括已退房的和还在居住的');
            $content->body($this->grid());
        });
    }

    /**
     * ajax请求，根据身份证号查找人员
     * @param Request $request
     * @return array
     */
    public function byIdentify(Request $request)
    {
        $person = Person::where('identify', $request->identify)->first();
        return $person ? ['status' => 1, 'data' => $person] : ['status' => 0, 'data' => []];
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Person::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->name('姓名');
            $grid->gender('性别');
            $grid->identify('身份证号');
            $grid->department('部门');
            $grid->education('学历')->display(function ($value) {
                return Person::$educationMap[$value];
            });
            $grid->rooms('居住过的房间')->display(function ($rooms) {
                // 每行显示3个房间
                $roomsArr = array_chunk($rooms, 3);
                $roomsArr = array_map(function ($rooms) {
                    $rooms = array_map(function ($room) {
                        return "<span class='label label-success' style='display:inline-block;'>{$room['title']}</span>";
                    }, $rooms);
                    return join('&nbsp;', $rooms);
                }, $roomsArr);
                return join('<br>', $roomsArr);
            });
            $grid->entered_at('入住公寓时间');
            $grid->phone_number('电话');
            $grid->contract_start('劳动合同起始日');
            $grid->contract_end('劳动合同结束日');

            // 在住的人的id
            $livingPersonIds = Record::where('status', 0)->distinct()->pluck('person_id')->toArray();
            $grid->status('状态')->display(function () use ($livingPersonIds){
                return in_array($this->id, $livingPersonIds) ? '在住' : '已退房';
            });

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();

            $grid->filter(function($filter){
                $filter->like('name', '姓名');
                $filter->in('education', '学历')->multipleSelect(Person::$educationMap);
                $filter->like('phone_number', '电话');
                $filter->like('identify', '身份证号');
                $filter->like('department', '部门');
                $filter->disableIdFilter();
            });

            $grid->exporter(new PersonExporter());
        });
    }
}
