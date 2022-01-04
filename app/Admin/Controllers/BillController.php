<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Buttons\DeleteButton;
use App\Admin\Extensions\Buttons\EditButton;
use App\Admin\Extensions\Exporters\BillExporter;
use App\Admin\Extensions\Tools\Common\Create;
use App\Admin\Extensions\Tools\Bills\ImportBills;
use App\Admin\Traits\BackOrRedirect;
use App\Exceptions\ExcelInvalidBillDataException;
use App\Http\Requests\BillImportRequest;
use App\Http\Requests\BillRequest;
use App\Models\Bill;
use App\Models\BillType;
use App\Services\BillService;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BillController extends Controller
{
    use ModelForm, BackOrRedirect;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('费用');
            $content->body($this->grid());
        });
    }
    public function create()
    {
        $billTypes = BillType::where('is_using', true)->pluck('title');
        $view = view('bills.create', compact('billTypes'));
        return Admin::content(function (Content $content) use ($view) {
            $content->header('费用');
            $content->body($view);
        });
    }

    /**
     * 存储
     * 由于每次添加时都是批量添加，考虑到性能问题，没有使用create，故而需要手动处理数据，也没有使用observer
     * @param BillRequest $request
     * @param BillService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store(BillRequest $request, BillService $service)
    {
        $service->batchStore($request->input('items'));
        return $this->backOrRedirectTo('bills.index');
    }

    public function chargePage(Request $request, BillService $service)
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : '';
        // 没有时，默认是 location
        $field = ($request->has('field') && $request->input('field') === 'name') ? 'name' : 'location';

        $groupedBills = $service->getUnpayedGroupedBills($keyword, $field);

        $view = view('bills.charge', compact('groupedBills'));
        return Admin::content(function (Content $content) use ($view) {
            $content->header('缴费');
            $content->body($view);
        });
    }

    public function charge(Request $request, BillService $service)
    {
        $id = $request->input('id');
        $payedAt = $request->input('payed_at');
        $chargeMode = $request->input('charge_mode');
        $ids = strpos($id, ',') !== false ? explode(',', $id) : [$id];

        if ($service->charge($ids, $payedAt, $chargeMode)) {
            return response()->json(['status' => 1, 'message' => '缴费成功']);
        }
        return response()->json(['status' => 0, 'message' => '缴费失败']);
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('修改费用');

            $content->body($this->editForm($id)->edit($id));
        });
    }

    public function update($id)
    {
        return $this->editForm($id)->update($id);
    }

    public function destroy($id)
    {
        if ($this->editForm($id)->destroy($id)) {
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

    public function importPage()
    {
        return Admin::content(function (Content $content) {
            $content->header('导入');
            $content->row(view('bills.import-format'));
            $content->body($this->importForm());
        });
    }

    public function import(BillImportRequest $request)
    {
        $file = $request->file('file');
        $extension = $file->extension();
        // 当前时间加一个随机字符串
        $fileName = date('YmdHis') . '_' . str_random(4) . '.' . $extension;
        $file->storeAs(date('Y'), $fileName, 'bills_import');

        // 上传到数据库
        $this->uploadExcel($file);

        return $this->backOrRedirectTo('bills.index', '导入成功');
    }

    public function importErrorPage()
    {
        return Admin::content(function (Content $content) {
            $content->header('导入结果');
            $content->body(view('bills.import-error'));
        });
    }

    public function statistics(Request $request, BillService $service)
    {
        // 格式： ['bill_type_id' => 'total_cost']
        $statistics = $service->getStatisticsByOptions($request->all());
        $billTypes = BillType::pluck('title', 'id')->toArray();

        $billsStatistics = [];
        // 格式： ['bill_type_title' => 'total_cost']
        foreach ($statistics as $typeId => $cost) {
            $billsStatistics[$billTypes[$typeId]] = $cost;
        }
        ksort($billsStatistics, SORT_STRING);

        $view = view('bills.statistics', compact('billTypes', 'billsStatistics'));
        return Admin::content(function (Content $content) use ($view) {
            $content->header('费用统计');
            $content->body($view);
        });
    }

    protected function grid()
    {
        return Admin::grid(Bill::class, function (Grid $grid) {

            $grid->model()->orderBy('id', 'desc');

            $grid->column('is_refund', '缴/退')->display(function ($value) {
                return $value ? '<span style="color:red">退费</span>' : '';
            });
            $grid->location('房间号/位置');
            $grid->name('姓名');
            $grid->type()->title('类型');
            $grid->cost('费用');
            $grid->explain('费用说明');
            $grid->remark('备注');
            $grid->charge_mode('缴费方式');
            $grid->payed_at('缴费时间')->display(function ($value) {
                return substr($value, 0, 10);
            });
            $grid->created_at('生成时间')->display(function ($value) {
                return substr($value, 0, 10);
            });

            $grid->actions(function ($actions) {
                $bill = $actions->row;
                $user = Admin::user();
                $actions->disableDelete();
                $actions->disableEdit();
                if ($user->can('bills.destroy')) {
                    $actions->append(new DeleteButton($bill->id, 'bills.destroy'));
                }
                if ($user->can('bills.edit')) {
                    $actions->append(new EditButton($bill->id, 'bills.edit'));
                }
            });

            $grid->tools(function ($tools) {
                $tools->append(new Create('bills.create'));
                $tools->append(new ImportBills());
            });

            $grid->filter(function($filter){
                $types = BillType::pluck('title', 'id')->toArray();
                $filter->equal('location', '位置/房间号');
                $filter->equal('name', '姓名');
                $filter->in('bill_type_id', '费用类型')->multipleSelect($types);
                $filter->where(function ($query) {
                    $dates = explode('-', $this->input);
                    switch (count($dates)) {
                        case 1: // 年
                            $query->payAtYear($dates[0]);
                            break;
                        case 2: // 年月
                            $query->payAtYear($dates[0])->payAtMonth($dates[1]);
                            break;
                        case 3: // 年月日
                            $query->payAtYear($dates[0])->payAtMonth($dates[1])->payAtDay($dates[2]);
                    }
                }, '缴费日期')->placeholder('格式：2018-9-28，或 2018-9，或 2018');
                $filter->where(function ($query) {
                    if ($this->input === 'yes') {
                        $query->whereNotNull('payed_at');
                    }
                    if ($this->input === 'no') {
                        $query->whereNull('payed_at');
                    }
                }, '是否缴费')->radio(['all' => '全部', 'no' => '未缴费', 'yes' => '已缴费']);
                $filter->where(function ($query) {
                    if ($this->input === 'not_refund') {
                        $query->withoutRefund();
                    }
                    if ($this->input === 'refund') {
                        $query->onlyRefund();
                    }
                }, '缴费/退费')->radio(['all' => '全部', 'not_refund' => '缴费', 'refund' => '退费']);
                $filter->disableIdFilter();
            });

            $grid->disableRowSelector();
            $grid->disableCreateButton();

            $grid->exporter(new BillExporter());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function editForm($id)
    {
        return Admin::form(Bill::class, function (Form $form) use ($id) {
            $types = BillType::where('is_using', true)->orderBy('title')->pluck('title', 'id');
            $payed = Bill::where('id', $id)->payed()->exists();

            $form->select('is_refund', '缴费/退费')->options(['0'=>'缴费', '1'=>'退费']);
            $form->text('location', '位置')
                ->rules('required', [
                    'required' => '必须填写',
                ]);
            $form->text('name', '姓名');
            $form->select('bill_type_id', '费用类型')->options($types);
            $form->text('cost', '金额')->rules('required|numeric', [
                    'required' => '必须填写',
                    'numeric' => '必须是一个数字',
                ]);
            $form->textarea('remark', '备注')->rows(3);
            $form->textarea('explain', '费用说明')->rows(3);
            if ($payed) {
                $form->date('payed_at', '缴费时间');
            }

            $form->disableReset();
        });
    }

    protected function importForm()
    {
        return Admin::form(Bill::class, function (Form $form) {
            $form->file('file', '选择文件');
            $form->setAction(route('bills.import'));
            $form->disableReset();
        });
    }

    protected function uploadExcel($file)
    {
        Excel::selectSheetsByIndex(0)->load($file, function ($reader) {
            $data = $reader->all()->toArray();
            if (empty($data)) {
                throw new ExcelInvalidBillDataException('请至少上传一组数据');
            };
            // 列数错误
            if (count($data[0]) < 5) {
                throw new ExcelInvalidBillDataException('格式错误，请满足格式要求');
            }
            $billTypes = BillType::pluck('id', 'title')->toArray();
            $billTypeTitles = array_keys($billTypes);
            $items = [];
            foreach ($data as $k => $d) {
                // 过滤掉房间号/位置为空的值
                if (empty($d[0])) {
                    continue;
                }
                if (empty($d[2])) {
                    throw new ExcelInvalidBillDataException("在文件第" . ($k + 2) . "行，费用类型不能为空");
                }
                if (!in_array(trim($d[2]), $billTypeTitles)) {
                    throw new ExcelInvalidBillDataException("在文件第" . ($k + 2) . "行，费用类型不存在或错误");
                }
                if (!is_numeric($d[3])) {
                    throw new ExcelInvalidBillDataException("在文件第" . ($k + 2) . "行，金额不是一个数字");
                }
                $t['location'] = htmlspecialchars(trim($d[0]));
                $t['name'] = htmlspecialchars(trim($d[1]));
                $t['bill_type'] = $d[2];
                $t['cost'] = $d[3];
                $t['explain'] = htmlspecialchars(trim($d[4]));
                $t['payed_at'] = (isset($d[5]) && strtotime($d[5])) ? \Carbon\Carbon::parse($d[5]) : null;
        
                $items[] = $t;
            }
            $service = new BillService();
            if (!$service->batchStore($items)) {
                throw new ExcelInvalidBillDataException("内部存储错误");
            }
        });
    }
}
