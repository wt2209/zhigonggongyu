<?php

namespace App\Admin\Controllers;

use App\Admin\Traits\BackOrRedirect;
use App\Admin\Traits\RepairFormAndGrid;
use App\Http\Requests\RepairMaterialRequest;
use App\Models\Repair;
use App\Models\RepairItem;
use App\Models\RepairType;
use App\Services\RepairService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    use ModelForm, RepairFormAndGrid, BackOrRedirect;

    private $service;

    public function __construct(RepairService $repairService)
    {
        $this->service = $repairService;
    }

    public function unreviewed()
    {
        return Admin::content(function (Content $content) {
            $content->header('未审核项目');
            $content->row(view('repairs.steps', ['currentStep' => Repair::ITEM_UNREVIEWED]));
            $content->body($this->unreviewedGrid());
        });
    }

    public function unpassed()
    {
        return Admin::content(function (Content $content) {
            $content->header('未通过项目');
            $content->body($this->unpassedGrid());
        });
    }

    public function unprinted()
    {
        return Admin::content(function (Content $content) {
            $content->header('未打印项目');
            $content->row(view('repairs.steps', ['currentStep' => Repair::ITEM_UNPRINTED]));
            $content->body($this->unprintedGrid());
        });
    }

    public function unfinished()
    {
        return Admin::content(function (Content $content) {
            $content->header('正在维修项目');
            $content->row(view('repairs.steps', ['currentStep' => Repair::ITEM_UNFINISHED]));
            $content->body($this->unfinishedGrid());
        });
    }

    public function finished()
    {
        return Admin::content(function (Content $content) {
            $content->header('已完工项目');
            $content->row(view('repairs.steps', ['currentStep' => Repair::ITEM_FINISHED]));
            $content->body($this->finishedGrid());
        });
    }

    public function show($id)
    {
        $repair = Repair::finished()->where('id', $id)->first();
        if (!$repair) {
            abort(404);
        }
        return Admin::content(function (Content $content) use ($repair) {
            $content->header('维修详情');
            $content->row(function (Row $row) use ($repair) {
                $oldTypes = $repair->types()->with('type')->get();
                $typesTotal = $oldTypes->sum(function ($type) {
                    return $type->price * $type->total;
                });
                $oldItems = $repair->items()->with('item')->get();
                $itemsTotal = $oldItems->sum(function ($item) {
                    return $item->price * $item->total;
                });
                $row->column(6, $this->showForm()->edit($repair->id));
                $row->column(6, view(
                    'repairs.material',
                    compact('oldTypes', 'oldItems', 'itemsTotal', 'typesTotal'))
                );
            });
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('修改报修信息');
            $content->body($this->form()->edit($id));
        });
    }

    public function reviewPage($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('审核');
            $content->body($this->reviewForm()->edit($id));
        });
    }

    public function review(Request $request)
    {
        $this->service->checkReviewed($request->id);
        return $this->reviewForm()->update($request->id);
    }

    public function print($id)
    {
        $this->service->print($id);
        return response()->json(['status' => 1]);
    }

    public function finishPage($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('完工');
            $content->body($this->finishForm()->edit($id));
        });
    }

    public function materialPage($id)
    {
        $repair = Repair::findOrFail($id);
        $types = RepairType::all();
        $types = $types->map(function ($item, $key) {
            $item->display = $item->title;
            return $types[$key] = $item;
        });
        $items = RepairItem::all();
        $items = $items->map(function ($item, $key) {
            $item->display = $item->name . ' | ' . $item->feature;
            return $items[$key] = $item;
        });
        $oldTypes = $repair->types()->with('type')->get();
        $oldTypes = $oldTypes->map(function ($item, $key) {
            $item->display = $item->type->title;
            return $oldTypes[$key] = $item;
        });
        $oldItems = $repair->items()->with('item')->get();
        $oldItems = $oldItems->map(function ($item, $key) {
            $item->display = $item->item->name . ' | ' . $item->item->feature;
            return $oldItems[$key] = $item;
        });
        return view(
            'repairs.create_material',
            compact('repair', 'types', 'items', 'oldItems', 'oldTypes')
        );
    }

    /**
     * 存储用工用料
     * @param RepairMaterialRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function material(RepairMaterialRequest $request)
    {
        $this->service->updateOrCreateMaterials($request);
        return $this->backOrRedirectTo('repairs.finished');
    }

    public function finish(Request $request)
    {
        $this->service->checkFinished($request->id);
        return $this->finishForm()->update($request->id);
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('报修');
            $content->row(function (Row $row) {
                $row->column(8, $this->form());
                $row->column(4, view('repairs.history'));
            });
        });
    }

    public function history(Request $request) {
        $data = Repair::where('location', 'like', $request->location)->orderBy('id', 'desc')->limit(10)->get();
        return response()->json(['status'=>1, 'data'=>$data]);
    }

    public function batchReview(Request $request)
    {
        if ($this->service->batchReview($request->input('ids'))) {
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);
    }

    public function batchFinish(Request $request)
    {
        if ($this->service->batchFinish($request->input('ids'))) {
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);
    }
}
