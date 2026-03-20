<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\GalleryCatalogue\StoreRequest;
use App\Http\Requests\RealEstate\GalleryCatalogue\UpdateRequest;
use App\Services\V2\Impl\RealEstate\GalleryCatalogueService;
use App\Models\Language;

class GalleryCatalogueController extends Controller
{

    private $service;
    protected $language;

    public function __construct(
        GalleryCatalogueService $service
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
        $this->authorize('modules', 'gallery.catalogue.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.gallery.catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'gallery.catalogue.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $template = 'backend.gallery.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'gallery.catalogue.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('gallery.catalogue.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $template = 'backend.gallery.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'gallery.catalogue.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'gallery.catalogue.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'gallery.catalogue.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'gallery.catalogue.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'gallery.catalogue.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.gallery.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'gallery.catalogue.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'gallery.catalogue.index');
    }

    private function config(): array
    {
        return [
            'model' => 'GalleryCatalogue',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Nhóm Thư Viện Ảnh',
                'table' => 'Danh sách Nhóm Thư Viện Ảnh'
            ],
            'create' => [
                'title' => 'Thêm mới Nhóm Thư Viện Ảnh'
            ],
            'update' => [
                'title' => 'Cập nhật Nhóm Thư Viện Ảnh'
            ],
            'delete' => [
                'title' => 'Xóa Nhóm Thư Viện Ảnh'
            ]
        ];
    }
}
