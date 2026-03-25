<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use App\Services\V1\RealEstate\RealEstateService;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Http\Requests\RealEstate\StoreRealEstateRequest;
use App\Http\Requests\RealEstate\UpdateRealEstateRequest;
use Illuminate\Http\Request;

class RealEstateController extends Controller
{
    protected $realEstateService;
    protected $realEstateRepository;
    protected $language;
    protected $nestedset;
    protected $config;

    public function __construct(
        RealEstateService $realEstateService,
        RealEstateRepository $realEstateRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = \App\Models\Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->realEstateService = $realEstateService;
        $this->realEstateRepository = $realEstateRepository;
        $this->initialize();
    }

    private function initialize()
    {
        $this->nestedset = new \App\Classes\Nestedsetbie([
            'table' => 'real_estate_catalogues',
            'foreignkey' => 'real_estate_catalogue_id',
            'language_id' =>  $this->language,
            'join' => 'real_estate',
        ]);

        $this->config = [
            'module' => 'realEstate',
            'language' => $this->language,
        ];
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'realEstate.index');
        $realEstates = $this->realEstateService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/library/location.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'RealEstate',
            'seo' => __('messages.realEstate')
        ];
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.realestate.realestate.index';
        return view('backend.dashboard.layout', compact('realEstates', 'config', 'template', 'dropdown'));
    }

    public function create()
    {
        $this->authorize('modules', 'realEstate.create');
        $config = $this->configData();
        $config['method'] = 'create';
        $config['seo'] = __('messages.realEstate');
        $dropdown = $this->nestedset->Dropdown();
        $dropdowns = $this->getDropdowns();
        $provinces = $this->getProvincesFromJson('after');
        $old_provinces = $this->getProvincesFromJson('before');
        $amenityCatalogues = $this->getAmenityCatalogues();
        $projects = \App\Models\Project::with(['languages' => function($query){
            $query->where('language_id', $this->language);
        }])->get();
        $template = 'backend.realestate.realestate.store';
        return view('backend.dashboard.layout', compact('config', 'template', 'dropdown', 'dropdowns', 'provinces', 'old_provinces', 'amenityCatalogues', 'projects'));
    }

    public function store(StoreRealEstateRequest $request)
    {
        if ($this->realEstateService->create($request, $this->language)) {
            return redirect()->route('real.estate.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->route('real.estate.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'realEstate.edit');
        $realEstate = $this->realEstateRepository->getRealEstateById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'edit';
        $config['seo'] = __('messages.realEstate');
        $dropdown = $this->nestedset->Dropdown();
        $dropdowns = $this->getDropdowns();
        $provinces = $this->getProvincesFromJson('after');
        $old_provinces = $this->getProvincesFromJson('before');
        $amenityCatalogues = $this->getAmenityCatalogues();
        $projects = \App\Models\Project::with(['languages' => function($query){
            $query->where('language_id', $this->language);
        }])->get();
        $template = 'backend.realestate.realestate.store';
        return view('backend.dashboard.layout', compact('config', 'realEstate', 'template', 'dropdown', 'dropdowns', 'provinces', 'old_provinces', 'amenityCatalogues', 'projects'));
    }

    private function getProvincesFromJson($source)
    {
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if (!\Illuminate\Support\Facades\File::exists($filePath)) {
            return [];
        }
        $data = json_decode(\Illuminate\Support\Facades\File::get($filePath), true);
        $result = [];
        foreach ($data as $item) {
            $result[$item['codename']] = $item['name'];
        }
        return $result;
    }

    public function update($id, UpdateRealEstateRequest $request)
    {
        if ($this->realEstateService->update($id, $request, $this->language)) {
            return redirect()->route('real.estate.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->route('real.estate.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'realEstate.delete');
        $realEstate = $this->realEstateRepository->getRealEstateById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'delete';
        $config['seo'] = __('messages.realEstate');
        $template = 'backend.realestate.realestate.delete';
        return view('backend.dashboard.layout', compact('realEstate', 'template', 'config'));
    }

    public function destroy($id)
    {
        if ($this->realEstateService->destroy($id)) {
            return redirect()->route('real.estate.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('real.estate.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function getAmenityCatalogues()
    {
        return \App\Models\AmenityCatalogue::with(['amenities.languages' => function($query){
            $query->where('language_id', $this->language);
        }, 'languages' => function($query){
            $query->where('language_id', $this->language);
        }])->get();
    }

    private function getDropdowns()
    {
        $codes = ['loai_gia', 'loai_giao_dich', 'huong_nha', 'huong_ban_cong', 'lau', 'loai_dat', 'phap_ly', 'noi_that'];
        $result = [];
        foreach ($codes as $code) {
            $catalogue = \App\Models\AttributeCatalogue::with(['attributes.languages' => function ($query) {
                $query->where('language_id', $this->language);
            }])->where('code', $code)->first();

            if ($catalogue) {
                $temp = [];
                foreach ($catalogue->attributes as $attr) {
                    $temp[$attr->id] = $attr->languages->first()->pivot->name ?? '';
                }
                $result[$code] = $temp;
            }
        }
        return $result;
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/location.js',
                'backend/library/realestate.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'extendJs' => true
        ];
    }
}
