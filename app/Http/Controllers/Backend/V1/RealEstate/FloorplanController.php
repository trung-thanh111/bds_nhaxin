<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\RealEstate\FloorplanService;
use App\Repositories\RealEstate\FloorplanRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Http\Requests\RealEstate\StoreFloorplanRequest;
use App\Http\Requests\RealEstate\UpdateFloorplanRequest;
use App\Models\Language;

class FloorplanController extends Controller
{
    protected $floorplanService;
    protected $floorplanRepository;
    protected $realEstateRepository;
    protected $language;

    public function __construct(
        FloorplanService $floorplanService,
        FloorplanRepository $floorplanRepository,
        RealEstateRepository $realEstateRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });

        $this->floorplanService = $floorplanService;
        $this->floorplanRepository = $floorplanRepository;
        $this->realEstateRepository = $realEstateRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'floorplan.index');
        $floorplans = $this->floorplanService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Floorplan',
        ];
        $config['seo'] = __('messages.floorplan');
        $template = 'backend.realestate.floorplan.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'floorplans'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'floorplan.create');
        $config = $this->configData();
        $config['seo'] = __('messages.floorplan');
        $config['method'] = 'create';
        $realEstates = $this->getRealEstateDropdown();
        $template = 'backend.realestate.floorplan.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'realEstates'
        ));
    }

    public function store(StoreFloorplanRequest $request)
    {
        if ($this->floorplanService->create($request, $this->language)) {
            return redirect()->route('floorplan.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id, Request $request)
    {
        $this->authorize('modules', 'floorplan.edit');
        $floorplan = $this->floorplanRepository->getFloorplanById($id, $this->language);
        $queryUrl = $request->getQueryString();
        $config = $this->configData();
        $config['seo'] = __('messages.floorplan');
        $config['method'] = 'edit';
        $realEstates = $this->getRealEstateDropdown();
        $template = 'backend.realestate.floorplan.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'floorplan',
            'realEstates',
            'queryUrl'
        ));
    }

    public function update($id, UpdateFloorplanRequest $request)
    {
        $queryUrl = base64_decode($request->getQueryString());
        if ($this->floorplanService->update($id, $request, $this->language)) {
            return redirect()->route('floorplan.index', $queryUrl)->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'floorplan.delete');
        $config['seo'] = __('messages.floorplan');
        $floorplan = $this->floorplanRepository->getFloorplanById($id, $this->language);
        $template = 'backend.realestate.floorplan.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'floorplan',
            'config',
        ));
    }

    public function destroy($id)
    {
        if ($this->floorplanService->destroy($id)) {
            return redirect()->route('floorplan.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function getRealEstateDropdown()
    {
        $realEstates = \App\Models\RealEstate::select([
                'real_estates.id',
                'tb2.name'
            ])
            ->join('real_estate_language as tb2', 'tb2.real_estate_id', '=', 'real_estates.id')
            ->where('tb2.language_id', '=', $this->language)
            ->get();
            
        $temp = [0 => '[Chọn Bất Động Sản]'];
        foreach($realEstates as $item){
            $temp[$item->id] = $item->name;
        }
        return $temp;
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
    }
}
