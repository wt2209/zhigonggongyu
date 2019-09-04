<?php
namespace App\Admin\Traits;

use App\Admin\Extensions\Buttons\DeleteButton;
use App\Admin\Extensions\Buttons\EditButton;
use App\Admin\Extensions\Buttons\RepairPrintButton;
use App\Admin\Extensions\Exporters\RepairFinishedExporter;
use App\Admin\Extensions\Tools\Repairs\Finish;
use App\Admin\Extensions\Tools\Repairs\Review;
use App\Models\Repair;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use DB;

trait RepairFormAndGrid
{
    protected function form()
    {
        return Admin::form(Repair::class, function (Form $form) {
            $form->text('location', '地点')
                ->rules('required', [
                    'required' => '必须填写',
                ]);
            $form->textarea('content', '报修内容')
                ->rules('required', [
                    'required' => '必须填写',
                ]);
            $form->text('name', '报修人');
            $form->text('phone_number', '报修人电话');
            $form->disableReset();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });

            //保存前回调
            $form->saving(function (Form $form) {
                // 修改时不改变状态
                if (!$form->model()->id) {
                    $user = Admin::user();
                    if ($user->can('repairs.review')) {
                        $form->model()->review_user_id = $user->id;
                        $form->model()->reviewed_at = now();
                        $form->model()->is_passed = true;
                    }
                    $form->model()->input_user_id = $user->id;
                }
            });

            // 成功后刷新
            $form->saved(function () {

                admin_toastr(trans('admin.save_succeeded'));
                return response()->redirectToRoute('repairs.create');
            });
        });
    }

    protected function showForm()
    {
        return Admin::form(Repair::class, function (Form $form) {
            $form->setTitle('详情');
            $form->display('location', '地点');
            $form->display('content', '报修内容');
            $form->display('name', '报修人');
            $form->display('phone_number', '电话');
            $form->display('inputer.name', '录入人');
            $form->display('reviewer.name', '审核人');
            $form->display('reviewed_at', '审核时间');
            $form->switch('is_passed', '是否通过')->attribute(['disabled' => 'disabled']);
            $form->display('review_remark', '审核说明');
            $form->display('printed_at', '首次打印');
            $form->display('finished_at', '完工时间');
            $form->display('finisher', '维修人');
            $form->display('finish_remark', '完工说明');
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
                $route = route('repairs.unfinished');
                $tools->add('<div class="btn-group pull-right" style="margin-right: 10px"><a href="' . $route . '" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a></div>');
            });
            $form->disableReset();
            $form->disableSubmit();
        });
    }

    protected function reviewForm()
    {
        return Admin::form(Repair::class, function (Form $form) {
            $form->hidden('id');
            $form->switch('is_passed', '是否通过')->default(true);
            $form->textarea('review_remark', '审核说明');
            $form->disableReset();

            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
                $route = route('repairs.unreviewed');
                $tools->add('<div class="btn-group pull-right" style="margin-right: 10px"><a href="' . $route . '" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a></div>');
            });
            //保存前回调
            $form->saving(function (Form $form) {
                $form->model()->review_user_id = Admin::user()->id;
                $form->model()->reviewed_at = now();
            });
            $form->setAction(route('repairs.review'));
        });
    }

    protected function finishForm()
    {
        return Admin::form(Repair::class, function (Form $form) {
            $form->hidden('id');
            $form->text('finisher', '维修人');
            $form->textarea('finish_remark', '完工说明');
            $form->disableReset();

            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
                $route = route('repairs.unfinished');
                $tools->add('<div class="btn-group pull-right" style="margin-right: 10px"><a href="' . $route . '" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a></div>');
            });
            //保存前回调
            $form->saving(function (Form $form) {
                $form->model()->finished_at = now();
            });
            $form->setAction(route('repairs.finish'));
        });
    }

    protected function unreviewedGrid()
    {
        return Admin::grid(Repair::class, function (Grid $grid) {

            $grid->model()->unreviewed();

            $grid->location('位置');
            $grid->content('报修内容');
            $grid->name('报修人');
            $grid->phone_number('电话');
            $grid->created_at('报修时间');
            $grid->inputer()->name('录入人');

            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $id = $actions->getKey();
                $user = Admin::user();
                $route = route('repairs.review-page', ['id' => $actions->getKey()]);
                if ($user->can('repairs.review')) {
                    $actions->append('<a href="' . $route . '" class="btn btn-warning btn-xs">审核</a>');
                }
                if ($user->can('repairs.edit')) {
                    $actions->append(new EditButton($id, 'repairs.edit'));
                }
                if ($user->can('repairs.destroy')) {
                    $actions->append(new DeleteButton($id, 'repairs.destroy'));
                }
            });

            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                    $batch->add('审核通过', new Review());
                });
            });
        });
    }

    protected function unpassedGrid()
    {
        return Admin::grid(Repair::class, function (Grid $grid) {

            $grid->model()->unpassed();

            $grid->location('位置');
            $grid->content('报修内容');
            $grid->name('报修人');
            $grid->phone_number('电话');
            $grid->created_at('报修时间');
            $grid->reviewed_at('审核时间');
            $grid->review_remark('未通过原因');

            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableRowSelector();
        });
    }

    protected function unprintedGrid()
    {
        return Admin::grid(Repair::class, function (Grid $grid) {

            $grid->model()->unprinted();

            $grid->location('位置');
            $grid->content('报修内容');
            $grid->name('报修人');
            $grid->phone_number('电话');
            $grid->created_at('报修时间');
            $grid->reviewed_at('审核时间');
            $grid->reviewer()->name('审核人');

            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disableRowSelector();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $id = $actions->getKey();
                $user = Admin::user();
                if ($user->can('repairs.print')) {
                    $actions->append(new RepairPrintButton($id));
                }
                if ($user->can('repairs.edit')) {
                    $actions->append(new EditButton($id, 'repairs.edit'));
                }
                if ($user->can('repairs.destroy')) {
                    $actions->append(new DeleteButton($id, 'repairs.destroy'));
                }
            });
        });
    }

    protected function unfinishedGrid()
    {
        return Admin::grid(Repair::class, function (Grid $grid) {

            $grid->model()->unfinished();

            $grid->location('位置');
            $grid->content('报修内容');
            $grid->name('报修人');
            $grid->phone_number('电话');
            $grid->created_at('报修时间');
            $grid->reviewed_at('审核时间');
            $grid->review_remark('审核说明');
            $grid->printed_at('打印时间');

            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $id = $actions->getKey();
                $route = route('repairs.finish-page', ['id' => $id]);
                $user = Admin::user();
                if ($user->can('repairs.print')) {
                    $actions->append(new RepairPrintButton($id));
                }
                if ($user->can('repairs.finish')) {
                    $actions->append('<a href="' . $route . '" class="btn btn-warning btn-xs">完工</a>');
                }
                if ($user->can('repairs.destroy')) {
                    $actions->append(new DeleteButton($id, 'repairs.destroy'));
                }
            });

            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                    $batch->add('批量完工', new Finish());
                });
            });
        });
    }

    protected function finishedGrid()
    {
        return Admin::grid(Repair::class, function (Grid $grid) {

            $grid->model()->finished();

            $grid->location('位置');
            $grid->content('报修内容');
            $grid->name('报修人');
            $grid->phone_number('电话');
            $grid->created_at('报修时间');
            $grid->reviewed_at('审核时间');
            $grid->review_remark('审核说明');
            $grid->finished_at('完工时间');

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $id = $actions->getKey();
                $detailRoute = route('repairs.show', ['id' => $id]);
                $materialRoute = route('repairs.material-page', ['id' => $id]);
                $user = Admin::user();
                if ($user->can('repairs.show')) {
                    $actions->append('<a href="' . $detailRoute . '" class="btn btn-success btn-xs" style="margin-right: 3px;">详情</a>');
                }
                if ($user->can('repairs.material')) {
                    $actions->append('<a href="' . $materialRoute . '" class="btn btn-warning btn-xs">材料</a>');
                }
                if ($user->can('repairs.destroy')) {
                    $actions->append(new DeleteButton($id, 'repairs.destroy'));
                }
            });

            $grid->filter(function ($filter) {
                $filter->like('location', '位置');
                $filter->like('content', '内容');
                $filter->where(function ($query) {
                    $dates = explode('-', $this->input);
                    switch (count($dates)) {
                        case 1: // 年
                            $query->finishedYear($dates[0]);
                            break;
                        case 2: // 年月
                            $query->finishedYear($dates[0])->finishedMonth($dates[1]);
                            break;
                        case 3: // 年月日
                            $query->finishedYear($dates[0])->finishedMonth($dates[1])->finishedDay($dates[2]);
                            break;
                    }
                }, '完工时间')->placeholder('格式：2018-9-28，或 2018-9，或 2018');
                $filter->where(function ($query) {
                    $dates = explode('-', $this->input);
                    switch (count($dates)) {
                        case 1: // 年
                            $query->whereYear('created_at', $dates[0]);
                            break;
                        case 2: // 年月
                            $query->whereYear('created_at', $dates[0])
                                ->whereMonth('created_at', $dates[1]);
                            break;
                        case 3: // 年月日
                            $query->whereYear('created_at', $dates[0])
                                ->whereMonth('created_at', $dates[1])
                                ->whereDay('created_at', $dates[2]);
                            break;
                    }
                }, '报修时间')->placeholder('格式：2018-9-28，或 2018-9，或 2018');
                $filter->disableIdFilter();
            });
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->exporter(new RepairFinishedExporter());
        });
    }
}
