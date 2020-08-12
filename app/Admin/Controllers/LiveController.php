<?php

namespace App\Admin\Controllers;

use App\Admin\Traits\BackOrRedirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\LiveMoveRequest;
use App\Http\Requests\LiveRenewRequest;
use App\Http\Requests\LiveRequest;
use App\Http\Requests\LiveUpdateRequest;
use App\Models\Record;
use App\Models\Room;
use App\Models\Type;
use App\Services\LiveService;
use Illuminate\Http\Request;

class LiveController extends Controller
{
    use BackOrRedirect;

    private $service;

    public function __construct(LiveService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $pageTitle = '居住信息';
        $rooms = $this->service->getRooms($request);
        $structure = $this->service->getRoomStructure();
        $types = Type::pluck('title', 'id');
        return view('lives.index', compact('rooms', 'structure', 'types', 'pageTitle'));
    }

    public function create(Request $request)
    {
        $roomId = $request->has('room_id') ? (int)$request->input('room_id') : 0;
        $room = Room::findOrFail($roomId);
        $types = Type::pluck('title', 'id');
        $pageTitle = '入住';
        return view('lives.create', compact('room', 'pageTitle', 'types'));
    }

    /**
     * 存储
     * @param LiveRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store(LiveRequest $request)
    {
        $this->service->store($request->all());
        return $this->backOrRedirectTo('lives.index');
    }

    /**
     * 调房
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function change($id)
    {
        $pageTitle = '调房';
        $record = Record::findOrFail($id);
        return view('lives.change', compact('record', 'pageTitle'));
    }

    /**
     * 完成调房
     * @param $id
     * @param LiveMoveRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function move($id, LiveMoveRequest $request)
    {
        $this->service->move($id, $request->input('move_to'));
        return $this->backOrRedirectTo('lives.index');
    }

    /**
     * 续签
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prolong($id)
    {
        $pageTitle = '续签';
        $record = Record::findOrFail($id);
        return view('lives.prolong', compact('record', 'pageTitle'));
    }

    /**
     * 完成续签
     * @param $id
     * @param LiveRenewRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function renew($id, LiveRenewRequest $request)
    {
        $this->service->renew($id, $request);
        return $this->backOrRedirectTo('lives.index');
    }

    public function edit($id)
    {
        $record = Record::with('person')->findOrFail($id);
        $types = Type::pluck('title', 'id');
        $pageTitle = '入住';
        return view('lives.edit', compact('record', 'pageTitle', 'types'));
    }

    public function update($id, LiveUpdateRequest $request)
    {
        $this->service->update($id, $request->input());
        return $this->backOrRedirectTo('lives.index');
    }

    /**
     * 完成退房
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function quit($id)
    {
        if ($this->service->quit($id)) {
            return response()->json(['status' => 1, 'message' => '退房成功']);
        }
        return response()->json(['status' => 0, 'message' => '退房失败']);
    }
}
