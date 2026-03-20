<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\Property\StoreRequest;
use App\Http\Requests\RealEstate\Property\UpdateRequest;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Models\Language;

class PropertyController extends Controller
{

    private $service;

    protected $language;

    public function __construct(
        PropertyService $service
    ) {
        $this->service = $service;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'property.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.property.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'property.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $template = 'backend.property.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'property.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('property.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $template = 'backend.property.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'property.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'property.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'property.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'property.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'property.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.property.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'property.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'property.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'Property',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Bất Động Sản',
                'table' => 'Danh sách Bất Động Sản'
            ],
            'create' => [
                'title' => 'Thêm mới Bất Động Sản'
            ],
            'update' => [
                'title' => 'Cập nhật Bất Động Sản'
            ],
            'delete' => [
                'title' => 'Xóa Bất Động Sản'
            ]
        ];
    }
}
