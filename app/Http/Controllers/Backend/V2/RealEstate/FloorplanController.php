<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\Floorplan\StoreRequest;
use App\Http\Requests\RealEstate\Floorplan\UpdateRequest;
use App\Services\V2\Impl\RealEstate\FloorplanService;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Models\Language;

class FloorplanController extends Controller
{

    private $service;
    protected $propertyService;
    protected $language;

    public function __construct(
        FloorplanService $service,
        PropertyService $propertyService
    ) {
        $this->service = $service;
        $this->propertyService = $propertyService;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'floorplan.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.floorplan.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'floorplan.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $properties = $this->propertyService->all();
        $template = 'backend.floorplan.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'properties'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'floorplan.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('floorplan.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $properties = $this->propertyService->all();
        $template = 'backend.floorplan.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record',
            'properties'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'floorplan.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'floorplan.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'floorplan.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'floorplan.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'floorplan.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.floorplan.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'floorplan.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'floorplan.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'Floorplan',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Mặt Bằng',
                'table' => 'Danh sách Mặt Bằng'
            ],
            'create' => [
                'title' => 'Thêm mới Mặt Bằng'
            ],
            'update' => [
                'title' => 'Cập nhật Mặt Bằng'
            ],
            'delete' => [
                'title' => 'Xóa Mặt Bằng'
            ]
        ];
    }
}
