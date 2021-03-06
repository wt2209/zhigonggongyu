<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Buttons\EditButton;
use App\Admin\Extensions\Buttons\DisableButton;
use App\Admin\Extensions\Buttons\EnableButton;
use App\Admin\Extensions\Exporters\BillTypeExporter;
use App\Admin\Extensions\Tools\Common\Create;
use App\Models\BillType;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class BillTypeController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('费用类型');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('修改费用类型');
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('新增费用类型');
            $content->body($this->form());
        });
    }

    public function disable(Request $request)
    {
        $type = BillType::findOrFail($request->id);
        $type->is_using = false;
        $type->save();
        return response()->json([
            'status'  => true,
            'message' => '禁用成功',
        ]);
    }
    public function enable(Request $request)
    {
        $type = BillType::findOrFail($request->id);
        $type->is_using = true;
        $type->save();
        return response()->json([
            'status'  => true,
            'message' => '启用成功',
        ]);
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(BillType::class, function (Grid $grid) {
            $grid->title('名称');
            $grid->turn_in('是否上交财务')->display(function ($value) {
                return $value ? '<i class="fa fa-fw fa-check text-green"></i>' : '<i class="fa fa-fw fa-close text-red"></i>';
            });
            $grid->remark('备注');
            $grid->created_at('创建时间');

            $grid->actions(function ($actions) {
                $id = $actions->getKey();
                $row = $actions->row;
                $actions->disableDelete();
                $actions->disableEdit();
                if (Admin::user()->can('bill_types.edit') && $row->is_using) {
                    $actions->append(new EditButton($id, 'bill_types.edit'));
                }
                if (Admin::user()->can('bill_types.disable') && $row->is_using) {
                    $actions->append(new DisableButton($id, 'bill_types.disable'));
                }
                if (Admin::user()->can('bill_types.enable') && !$row->is_using) {
                    $actions->append(new EnableButton($id, 'bill_types.enable'));
                }
            });

            $grid->tools(function ($tools) {
                $tools->append(new Create('bill_types.create'));
            });

            $grid->filter(function($filter){
                $filter->like('title', '名称');
                $filter->in('turn_in', '是否上交财务')->multipleSelect([0 => '否', 1 => '是']);
                $filter->disableIdFilter();
            });

            $grid->disableRowSelector();
            $grid->disableCreateButton();

            $grid->exporter(new BillTypeExporter());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(BillType::class, function (Form $form) {

            $form->text('title', '名称')->rules('required', [
                'required' => '必须填写',
            ]);
            $form->switch('turn_in', '上交财务')->default(true);
            $form->textarea('remark', '备注');
        });
    }
}
