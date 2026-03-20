<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\FloorplanRoom\StoreRequest;
use App\Http\Requests\RealEstate\FloorplanRoom\UpdateRequest;
use App\Services\V2\Impl\RealEstate\FloorplanRoomService;
use App\Services\V2\Impl\RealEstate\FloorplanService;
use App\Models\Language;

class FloorplanRoomController extends Controller
{

    private $service;
    protected $floorplanService;
    protected $language;

    public function __construct(
        FloorplanRoomService $service,
        FloorplanService $floorplanService
    ) {
        $this->service = $service;
        $this->floorplanService = $floorplanService;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'floorplan_room.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.floorplan_room.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'floorplan_room.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $floorplans = $this->floorplanService->all();
        $template = 'backend.floorplan_room.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'floorplans'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'floorplan_room.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('floorplan_room.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $floorplans = $this->floorplanService->all();
        $template = 'backend.floorplan_room.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record',
            'floorplans'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'floorplan_room.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'floorplan_room.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'floorplan_room.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'floorplan_room.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'floorplan_room.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.floorplan_room.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'floorplan_room.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'floorplan_room.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'FloorplanRoom',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Phòng Mặt Bằng',
                'table' => 'Danh sách Phòng Mặt Bằng'
            ],
            'create' => [
                'title' => 'Thêm mới Phòng Mặt Bằng'
            ],
            'update' => [
                'title' => 'Cập nhật Phòng Mặt Bằng'
            ],
            'delete' => [
                'title' => 'Xóa Phòng Mặt Bằng'
            ]
        ];
    }
}
