<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Buttons\EditButton;
use App\Admin\Extensions\Exporters\RoomExporter;
use App\Models\Room;
use Illuminate\Http\Request;
use Validator;
use App\Models\Type;
use App\Rules\RoomEmpty;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RoomController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('房间');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('修改房间');
            $content->body($this->form()->edit($id));
        });
    }

    public function update($id, Request $request)
    {
        /**
         * 检测房间是否时空房间
         * 经多次尝试，laravel-admin不能满足要求
         * 因此在此手动创建验证
         * 并将错误信息加到“类型”上
         */
        // 修改房间时不再检测是否是空房间 wt - 2020-8-12
        // $validator = Validator::make($request->all(), [
        //     'type_id' => new RoomEmpty($id),
        // ]);
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }
        return $this->form()->update($id);
    }

    public function updateRemark(Request $request) {
        $room = Room::findOrFail($request->id);
        $room->remark = $request->remark;
        $room->save();
        return response()->json(['status'=>1, 'message'=>'success']);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Room::class, function (Grid $grid) {
            $grid->model()->withCount('records')->with(['records', 'records.person']);
            $grid->title('房间号');
            $grid->person_number('标准人数');
            $grid->records_count('当前人数');
            $grid->records('当前人员')->display(function ($records) {
                if (empty($records)) {
                    return '';
                }
                $str = '';
                foreach ($records as $record) {
                     $str .= "<span class='label label-success'>{$record['person']['name']}</span> ";
                }
                return $str;
            });
            $grid->type()->title('所属类型');
            $grid->type()->fee_type('水电费')->display(function ($value) {
                return Type::$feeTypeMap[$value];
            });
            $grid->remark('备注');

            $grid->disableCreateButton();
            $grid->disableRowSelector();

            $grid->actions(function ($actions) {
                $id = $actions->getKey();
                $actions->disableDelete();
                $actions->disableEdit();
                if (Admin::user()->can('rooms.edit')) {
                    $actions->append(new EditButton($id, 'rooms.edit'));
                }
            });

            $grid->filter(function($filter){
                $types = Type::pluck('title', 'id')->toArray();
                $filter->where(function ($query) {
                    $query->where('title', 'like', "%{$this->input}%");
                }, '房间号');
                $filter->where(function ($query) {
                    $query->where('building', "{$this->input}#");
                }, '楼号');
                $filter->in('type_id', '类型')->multipleSelect($types);
                $filter->disableIdFilter();
            });

            $grid->exporter(new RoomExporter());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Room::class, function (Form $form) {
            $types = Type::pluck('title', 'id');
            $form->display('title', '房间号');
            $form->select('type_id', '所属类型')
                ->options($types)
                ->rules('required', [
                    'required' => '请选择所属类型',
                ]);
            $form->text('person_number', '标准人数')
                ->attribute('style', 'width:140px;')
                ->rules('required|integer', [
                    'required' => '请填写标准人数',
                    'integer' => '请填写一个数字',
                ]);
            $form->textarea('remark', '备注');

            $form->disableReset();
        });
    }
}
