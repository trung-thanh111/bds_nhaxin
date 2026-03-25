<?php

namespace App\Http\Controllers\Backend\V1\Amenity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\Amenity\AmenityService;
use App\Repositories\Amenity\AmenityRepository;
use App\Repositories\Amenity\AmenityCatalogueRepository;
use App\Http\Requests\Amenity\StoreAmenityRequest;
use App\Http\Requests\Amenity\UpdateAmenityRequest;
use App\Models\Language;
use App\Classes\Nestedsetbie;

class AmenityController extends Controller
{
    protected $amenityService;
    protected $amenityRepository;
    protected $amenityCatalogueRepository;
    protected $language;

    public function __construct(
        AmenityService $amenityService,
        AmenityRepository $amenityRepository,
        AmenityCatalogueRepository $amenityCatalogueRepository
    ){
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });

        $this->amenityService = $amenityService;
        $this->amenityRepository = $amenityRepository;
        $this->amenityCatalogueRepository = $amenityCatalogueRepository;
    }

    public function index(Request $request){
        $this->authorize('modules', 'amenity.index');
        $amenities = $this->amenityService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Amenity',
        ];
        $config['seo'] = __('messages.amenity');
        $template = 'backend.amenity.amenity.index';
        $dropdown = $this->getDropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'amenities',
            'dropdown'
        ));
    }

    public function create(){
        $this->authorize('modules', 'amenity.create');
        $config = $this->configData();
        $config['seo'] = __('messages.amenity');
        $config['method'] = 'create';
        $dropdown = $this->getDropdown();
        $template = 'backend.amenity.amenity.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }

    public function store(StoreAmenityRequest $request){
        if($this->amenityService->create($request, $this->language)){
            return redirect()->route('amenity.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('amenity.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id, Request $request){
        $this->authorize('modules', 'amenity.edit');
        $amenity = $this->amenityRepository->getAmenityById($id, $this->language);
        $queryUrl = $request->getQueryString();
        $config = $this->configData();
        $config['seo'] = __('messages.amenity');
        $config['method'] = 'edit';
        $dropdown = $this->getDropdown();
        $template = 'backend.amenity.amenity.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'amenity',
            'dropdown',
            'queryUrl'
        ));
    }

    public function update($id, UpdateAmenityRequest $request){
        $queryUrl = base64_decode($request->getQueryString());
        if($this->amenityService->update($id, $request, $this->language)){
            return redirect()->route('amenity.index',$queryUrl)->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('amenity.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'amenity.delete');
        $amenity = $this->amenityRepository->getAmenityById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'delete';
        $config['seo'] = __('messages.amenity');
        $template = 'backend.amenity.amenity.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'amenity',
            'config'
        ));
    }

    public function destroy($id){
        if($this->amenityService->destroy($id, $this->language)){
            return redirect()->route('amenity.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('amenity.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function getDropdown(){
        $nestedset = new Nestedsetbie([
            'table' => 'amenity_catalogues',
            'foreignkey' => 'amenity_catalogue_id',
            'language_id' =>  $this->language,
        ]);
        return $nestedset->Dropdown();
    }

    private function configData(){
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
