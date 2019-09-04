<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Buttons\EditButton;
use App\Admin\Extensions\Exporters\RepairTypeExporter;
use App\Admin\Extensions\Tools\Common\Create;
use App\Models\RepairType;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RepairTypeController extends Controller
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
            $content->header('工种');
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
            $content->header('修改工种');
            $content->description('不会修改已经录入系统的维修项目所用工种');
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
            $content->header('新增工种');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(RepairType::class, function (Grid $grid) {
            $grid->title('工种');
            $grid->price('单价');
            $grid->created_at('创建时间');
            $grid->disableRowSelector();

            $grid->actions(function ($actions) {
                $id = $actions->getKey();
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->append(new EditButton($id, 'repair_types.edit'));
            });
            $grid->tools(function ($tools) {
                $tools->append(new Create('repair_types.create'));
            });
            $grid->disableCreateButton();

            $grid->disableFilter();
            $grid->exporter(new RepairTypeExporter());

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(RepairType::class, function (Form $form) {
            $form->text('title', '工种')
                ->rules('required', [
                    'required' => '必须填写'
                ]);
            $form->text('price', '单价')
                ->rules('required|numeric', [
                    'required' => '必须填写',
                    'numeric' => '必须是一个数字'
                ]);

            $form->disableReset();
        });
    }
}
