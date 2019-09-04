<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Exporters\RenewalExporter;
use App\Models\Person;
use App\Models\Record;
use App\Models\Renewal;

use App\Models\Room;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;

class RenewalController extends Controller
{

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('续签记录');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        return Admin::grid(Renewal::class, function (Grid $grid) {
            $grid->model()->with(['record', 'record.room', 'record.person']);
            $grid->column('record.room', '房间号')->display(function ($room) {
                return $room['title'];
            });
            $grid->column('record.person', '姓名')->display(function ($room) {
                return $room['name'];
            });
            $grid->end_at('原租期结束日');
            $grid->new_end_at('新租期结束日');
            $grid->created_at('续签日期');

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->where(function ($query) {
                    $personIds = Person::where('name', 'like', "%{$this->input}%")->pluck('id')->toArray();
                    $recordIds = Record::whereIn('person_id', $personIds)->pluck('id')->toArray();
                    $query->whereIn('record_id', $recordIds);
                }, '姓名');
                $filter->where(function ($query) {
                    $roomId = Room::where('title', $this->input)->value('id');
                    $recordIds = Record::where('room_id', $roomId)->pluck('id')->toArray();
                    $query->whereIn('record_id', $recordIds);
                }, '房间号');
            });

            $grid->exporter(new RenewalExporter);
        });
    }
}
