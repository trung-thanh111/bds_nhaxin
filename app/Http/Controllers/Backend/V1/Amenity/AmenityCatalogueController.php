<?php

namespace App\Http\Controllers\Backend\V1\Amenity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\Amenity\AmenityCatalogueService;
use App\Repositories\Amenity\AmenityCatalogueRepository;
use App\Http\Requests\Amenity\StoreAmenityCatalogueRequest;
use App\Http\Requests\Amenity\UpdateAmenityCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class AmenityCatalogueController extends Controller
{
    protected $amenityCatalogueService;
    protected $amenityCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        AmenityCatalogueService $amenityCatalogueService,
        AmenityCatalogueRepository $amenityCatalogueRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->amenityCatalogueService = $amenityCatalogueService;
        $this->amenityCatalogueRepository = $amenityCatalogueRepository;
    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'amenity_catalogues',
            'foreignkey' => 'amenity_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'amenity.catalogue.index');
        $amenityCatalogues = $this->amenityCatalogueService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'AmenityCatalogue',
        ];
        $config['seo'] = __('messages.amenityCatalogue');
        $template = 'backend.amenity.catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'amenityCatalogues'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'amenity.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.amenityCatalogue');
        $config['method'] = 'create';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.amenity.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
        ));
    }

    public function store(StoreAmenityCatalogueRequest $request)
    {
        if ($this->amenityCatalogueService->create($request, $this->language)) {
            return redirect()->route('amenity.catalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id, Request $request)
    {
        $this->authorize('modules', 'amenity.catalogue.edit');
        $amenityCatalogue = $this->amenityCatalogueRepository->getAmenityCatalogueById($id, $this->language);
        $queryUrl = $request->getQueryString();
        $config = $this->configData();
        $config['seo'] = __('messages.amenityCatalogue');
        $config['method'] = 'edit';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.amenity.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'amenityCatalogue',
            'queryUrl'
        ));
    }

    public function update($id, UpdateAmenityCatalogueRequest $request)
    {
        $queryUrl = base64_decode($request->getQueryString());
        if ($this->amenityCatalogueService->update($id, $request, $this->language)) {
            return redirect()->route('amenity.catalogue.index', $queryUrl)->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'amenity.catalogue.delete');
        $amenityCatalogue = $this->amenityCatalogueRepository->getAmenityCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'delete';
        $config['seo'] = __('messages.amenityCatalogue');
        $template = 'backend.amenity.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'amenityCatalogue',
            'config'
        ));
    }

    public function destroy($id)
    {
        if ($this->amenityCatalogueService->destroy($id, $this->language)) {
            return redirect()->route('amenity.catalogue.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
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
