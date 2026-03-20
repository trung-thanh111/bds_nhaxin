<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\LocationHighlight\StoreRequest;
use App\Http\Requests\RealEstate\LocationHighlight\UpdateRequest;
use App\Services\V2\Impl\RealEstate\LocationHighlightService;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Models\Language;

class LocationHighlightController extends Controller
{

    private $service;
    protected $propertyService;
    protected $language;

    public function __construct(
        LocationHighlightService $service,
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
        $this->authorize('modules', 'location_highlight.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.location_highlight.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'location_highlight.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $properties = $this->propertyService->all();
        $template = 'backend.location_highlight.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'properties'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'location_highlight.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('location_highlight.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $properties = $this->propertyService->all();
        $template = 'backend.location_highlight.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record',
            'properties'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'location_highlight.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'location_highlight.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'location_highlight.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'location_highlight.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'location_highlight.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.location_highlight.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'location_highlight.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'location_highlight.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'LocationHighlight',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Tiện Ích Lân Cận',
                'table' => 'Danh sách Tiện Ích Lân Cận'
            ],
            'create' => [
                'title' => 'Thêm mới Tiện Ích Lân Cận'
            ],
            'update' => [
                'title' => 'Cập nhật Tiện Ích Lân Cận'
            ],
            'delete' => [
                'title' => 'Xóa Tiện Ích Lân Cận'
            ]
        ];
    }
}
