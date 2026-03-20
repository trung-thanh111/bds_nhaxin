<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\PropertyFacility\StoreRequest;
use App\Http\Requests\RealEstate\PropertyFacility\UpdateRequest;
use App\Services\V2\Impl\RealEstate\PropertyFacilityService;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Models\Language;

class PropertyFacilityController extends Controller
{

    private $service;
    protected $propertyService;
    protected $language;

    public function __construct(
        PropertyFacilityService $service,
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
        $this->authorize('modules', 'property_facility.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.property_facility.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'property_facility.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $properties = $this->propertyService->all();
        $template = 'backend.property_facility.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'properties'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'property_facility.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('property_facility.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $properties = $this->propertyService->all();
        $template = 'backend.property_facility.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record',
            'properties'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'property_facility.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'property_facility.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'property_facility.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'property_facility.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'property_facility.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.property_facility.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'property_facility.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'property_facility.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'PropertyFacility',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Tiện Ích BĐS',
                'table' => 'Danh sách Tiện Ích BĐS'
            ],
            'create' => [
                'title' => 'Thêm mới Tiện Ích BĐS'
            ],
            'update' => [
                'title' => 'Cập nhật Tiện Ích BĐS'
            ],
            'delete' => [
                'title' => 'Xóa Tiện Ích BĐS'
            ]
        ];
    }
}
