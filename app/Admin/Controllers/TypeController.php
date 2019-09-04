<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Buttons\DeleteButton;
use App\Admin\Extensions\Buttons\EditButton;
use App\Admin\Extensions\Exporters\TypeExporter;
use App\Admin\Extensions\Tools\Common\Create;
use App\Models\Type;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class TypeController extends Controller
{
    use ModelForm;

    /**
     * 类型首页
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('类型');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('修改类型');
            $content->body($this->form()->edit($id));
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('新增类型');
            $content->body($this->form());
        });
    }

    public function destroy($id)
    {
        if (Type::has('rooms')->where('id', $id)->exists()) {
            return response()->json([
                'status'  => false,
                'message' => '当前类型下存在房间，不能删除',
            ]);
        }
        if ($this->form()->destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Type::class, function (Grid $grid) {
            $grid->title('名称');
            $grid->fee_type('水电费')->display(function ($value) {
                return Type::$feeTypeMap[$value];
            });
            $grid->has_contract('存在租期')->display(function ($value) {
                return $value ? '<i class="fa fa-fw fa-check text-green"></i>' : '<i class="fa fa-fw fa-close text-red"></i>';
            });
            $grid->has_rent_fee('收取租金')->display(function ($value) {
                return ($value && $this->has_contract) ? '<i class="fa fa-fw fa-check text-green"></i>' : '<i class="fa fa-fw fa-close text-red"></i>';
            });
            $grid->remark('备注');
            $grid->created_at('创建时间');

            $grid->disableRowSelector();
            $grid->disableFilter();
            $grid->disableCreateButton();

            $grid->tools(function ($tools) {
                $tools->append(new Create('types.create'));
            });

            $grid->actions(function ($actions) {
                $id = $actions->getKey();
                $user = Admin::user();
                $actions->disableDelete();
                $actions->disableEdit();
                if ($user->can('types.edit')) {
                    $actions->append(new EditButton($id, 'types.edit'));
                }
                if ($user->can('types.destroy')) {
                    $actions->append(new DeleteButton($id, 'types.destroy'));
                }
            });

            $grid->exporter(new TypeExporter());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Type::class, function (Form $form) {
            $form->text('title', '名称')->rules(function ($form) {
                    // 如果不是编辑状态，则添加字段唯一验证
                    if (!$id = $form->model()->id) {
                        return 'required|unique:types,title';
                    } else {
                        return 'required';
                    }
                }, [
                    'required' => '请填写名称',
                    'unique' => '此名称已存在',
            ]);
            $form->select('fee_type','水电费')
                ->options(Type::$feeTypeMap)
                ->rules('required', [
                    'required' => '请选择收费类型',
            ]);
            $form->switch('has_contract', '存在租期');
            $form->switch('has_rent_fee', '收取租金');
            $form->textarea('remark', '备注');
            $form->disableReset();
        });
    }
}
