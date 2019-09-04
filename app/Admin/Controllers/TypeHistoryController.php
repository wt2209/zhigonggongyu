<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Exporters\TypeHistoryExporter;
use App\Models\Room;
use App\Models\TypeHistory;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class TypeHistoryController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('类型变动记录');
            $content->body($this->grid());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(TypeHistory::class, function (Grid $grid) {
            $grid->room()->title('房间号');
            $grid->fromType()->title('原类型');
            $grid->toType()->title('新类型');
            $grid->from_person_number('原人数');
            $grid->to_person_number('新人数');
            $grid->created_at('变动时间');

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();

            $grid->filter(function($filter){
                $filter->where(function ($query) {
                    $ids = Room::where('title', 'like', "%{$this->input}%")->pluck('id')->toArray();
                    $query->whereIn('room_id', $ids);
                }, '房间号');
                $filter->disableIdFilter();
            });

            $grid->exporter(new TypeHistoryExporter());
        });
    }
}
