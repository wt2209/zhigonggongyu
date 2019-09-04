<?php

namespace App\Admin\Controllers;

use App\Models\Person;
use App\Models\Record;
use App\Models\Room;
use App\Admin\Extensions\Exporters\RecordExporter;
use App\Models\Type;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RecordController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('入住记录');
            $content->description('');
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
        return Admin::grid(Record::class, function (Grid $grid) {
            // 加载所有数据，包括已经 softDelete 的数据
            $grid->model()->orderBy('status','desc')->orderBy('deleted_at', 'desc')->orderBy('id', 'desc')->withTrashed();

            $grid->room()->title('房间号');

            $grid->type()->title('类型');
            $grid->person()->name('姓名');
            $grid->person()->department('部门');
            $grid->person()->identify('身份证号');
            $grid->person()->entered_at('入住公寓');
            $grid->start_at('租期开始日')->display(function ($value) {
                return $this->type['has_contract'] ? $value : '';
            });
            $grid->end_at('租期结束日')->display(function ($value) {
                return $this->type['has_contract'] ? $value : '';
            });
            $grid->status('状态')->display(function ($value) {
                $ret = Record::$statusMap[$value];
                if ($value === Record::STATUS_MOVE) {
                    $ret .= '到' . $this->toRoom['title'];
                }
                return $ret;
            });
            $grid->deleted_at('退房/调房日');

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();

            $grid->filter(function ($filter) {
                $filter->in('status', '状态')->multipleSelect(Record::$statusMap);
                $filter->in('type_id', '类型')->multipleSelect(Type::pluck('title', 'id'));
                $filter->where(function ($query) {
                    $ids = Person::where('name', 'like', "%" . trim($this->input) . "%")->pluck('id');
                    $query->whereIn('person_id', $ids);
                }, '姓名');
                $filter->where(function ($query) {
                    $ids = Room::where('title', trim($this->input))->pluck('id');
                    $query->whereIn('room_id', $ids);
                }, '房间号');
                $filter->disableIdFilter();
            });

            $grid->exporter(new RecordExporter());
        });
    }
}
