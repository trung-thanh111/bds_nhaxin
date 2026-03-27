<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\RealEstate\ProjectService;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\AgentRepo;
use App\Services\V1\Core\WidgetService;
use App\Models\Attribute;
use Illuminate\Support\Facades\DB;

class SearchController extends FrontendController
{
    protected $realEstateCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $projectCatalogueRepository;
    protected $projectRepository;
    protected $projectService;
    protected $widgetService;
    protected $agentRepo;
    protected $attributeRepository;
    protected $amenityRepository;

    public function __construct(
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        ProjectCatalogueRepository $projectCatalogueRepository,
        ProjectRepository $projectRepository,
        ProjectService $projectService,
        WidgetService $widgetService,
        AgentRepo $agentRepo,
        \App\Repositories\Attribute\AttributeRepository $attributeRepository,
        \App\Repositories\Amenity\AmenityRepository $amenityRepository
    ) {
        parent::__construct();
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->projectRepository = $projectRepository;
        $this->projectService = $projectService;
        $this->widgetService = $widgetService;
        $this->agentRepo = $agentRepo;
        $this->attributeRepository = $attributeRepository;
        $this->amenityRepository = $amenityRepository;
    }

    public function index(Request $request)
    {
        $isProject = $request->filled('project_catalogue_id') || $request->input('type') == 'project';
        
        $system = $this->system;
        $config = $this->config($isProject);
        $agent = $this->agentRepo->findByCondition([['is_primary', '=', 1], ['publish', '=', 2]], false);
        
        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
            ['keyword' => 'product-category', 'children' => true],
        ], $this->language);

        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2], ['parent_id', '=', 0]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }]);

        $projectCatalogues = $this->projectCatalogueRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['lft', 'asc']);

        $houseDirections = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 3], ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['id', 'asc']);

        $furnitures = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 2], ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['id', 'asc']);

        $balconyDirections = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 4], ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['id', 'asc']);

        $amenities = $this->amenityRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['order', 'asc']);

        $newestRealEstates = $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['id', 'DESC'], [], [], 5);

        $featuredProjects = $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) { $q->where('language_id', $this->language); }], ['id', 'DESC'], [], [], 5);

        if ($isProject) {
            return $this->renderProjectSearch($request, compact(
                'system', 'config', 'agent', 'widgets', 'realEstateCatalogues', 'projectCatalogues',
                'houseDirections', 'furnitures', 'balconyDirections', 'amenities', 'newestRealEstates', 'featuredProjects'
            ));
        } else {
            $transactionType = $request->input('transaction_type', '74');
            $rootId = ($transactionType == '75') ? 16 : 1;
            $propertyTypes = $this->realEstateCatalogueRepository->findByCondition([
                ['parent_id', '=', $rootId],
                ['publish', '=', 2]
            ], true, ['languages' => function ($q) {
                $q->where('language_id', $this->language);
            }]);

            return $this->renderRealEstateSearch($request, compact(
                'system', 'config', 'agent', 'widgets', 'realEstateCatalogues', 'projectCatalogues',
                'houseDirections', 'furnitures', 'balconyDirections', 'amenities', 'newestRealEstates', 'featuredProjects',
                'propertyTypes'
            ));
        }
    }

    private function renderProjectSearch($request, $data)
    {
        $sorts = [
            'id:desc' => 'Mặc định',
            'area:desc' => 'Quy mô lớn đến nhỏ',
            'apartment_count:desc' => 'Nhiều căn hộ nhất',
            'created_at:desc' => 'Mới nhất'
        ];
        $currentSort = $request->input('sort') ?: 'id:desc';
        $sortArr = explode(':', $currentSort);
        $sort = ['projects.' . $sortArr[0], $sortArr[1]];

        $page = $request->input('page', 1);
        $projects = $this->projectService->paginate($request, $this->language, null, $page, ['path' => 'tim-kiem.html'], $sort);
        
        $provinceName = '';
        if ($request->filled('province_code')) {
            $provinceName = $this->getLocationNameFromJson('after', $request->input('province_code'));
        } elseif ($request->filled('old_province_code')) {
            $provinceName = $this->getLocationNameFromJson('before', $request->input('old_province_code'));
        }

        $seo = [
            'meta_title' => 'Tìm kiếm dự án ' . ($provinceName ? 'tại ' . $provinceName : '') . ' - ' . ($data['system']['seo_meta_title'] ?? ''),
            'canonical' => route('search.index'),
        ];
        
        $isProject = true;
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.component.project_list', compact('projects'))->render(),
                'total' => $projects->total(),
                'sortLabel' => $sorts[$currentSort] ?? 'Mặc định'
            ]);
        }

        return view('frontend.realestate.catalogue.search', array_merge($data, compact('projects', 'seo', 'sorts', 'isProject', 'currentSort', 'provinceName')));
    }

    private function renderRealEstateSearch($request, $data)
    {
        $transactionType = $request->input('transaction_type', '74');
        $priceField = ($transactionType == '75') ? 'price_rent' : 'price_sale';
        $sorts = [
            'id:desc' => 'Mặc định',
            $priceField . ':asc' => 'Giá thấp đến cao',
            $priceField . ':desc' => 'Giá cao đến thấp',
            'area:asc' => 'Diện tích nhỏ đến lớn',
            'area:desc' => 'Diện tích lớn đến nhỏ',
        ];
        
        $currentSort = $request->input('sort') ?: 'id:desc';
        $sortArr = explode(':', $currentSort);
        $sort = ['real_estates.' . $sortArr[0], $sortArr[1]];

        $page = $request->input('page', 1);
        $realEstates = $this->realEstateService->paginate($request, $this->language, null, $page, ['path' => 'tim-kiem.html'], $sort);
        
        $provinceName = '';
        if ($request->filled('province_code')) {
            $provinceName = $this->getLocationNameFromJson('after', $request->input('province_code'));
        } elseif ($request->filled('old_province_code')) {
            $provinceName = $this->getLocationNameFromJson('before', $request->input('old_province_code'));
        }

        $attributeIds = [];
        foreach ($realEstates as $re) {
            foreach(['price_unit', 'transaction_type', 'house_direction', 'ownership_type', 'balcony_direction', 'interior', 'land_type', 'floor'] as $field) {
                if($re->$field) $attributeIds[] = $re->$field;
            }
        }
        $attributeIds = array_unique(array_filter($attributeIds));
        $attributeMap = [];
        if (!empty($attributeIds)) {
            $attributeMap = Attribute::whereIn('id', $attributeIds)
                ->with(['languages' => function ($q) { $q->where('language_id', $this->language); }])
                ->get()->pluck('languages.0.pivot.name', 'id')->toArray();
        }

        $seo = [
            'meta_title' => 'Tìm kiếm bất động sản ' . ($provinceName ? 'tại ' . $provinceName : '') . ' - ' . ($data['system']['seo_meta_title'] ?? ''),
            'canonical' => route('search.index'),
        ];
        
        $isProject = false;

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.realestate.catalogue.listing_results', compact('realEstates', 'attributeMap', 'transactionType'))->render(),
                'total' => number_format($realEstates->total(), 0, ',', '.'),
                'sortLabel' => $sorts[$currentSort] ?? 'Mặc định'
            ]);
        }

        return view('frontend.realestate.catalogue.search', array_merge($data, compact('realEstates', 'attributeMap', 'seo', 'sorts', 'isProject', 'currentSort', 'transactionType', 'provinceName')));
    }

    private function config($isProject)
    {
        return [
            'language' => $this->language,
            'css' => [
                'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css',
                'frontend/resources/style.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/owl.carousel.min.js',
                'frontend/resources/library/js/carousel.js',
                'frontend/resources/library/js/filter.js',
            ],
        ];
    }

    private function getLocationNameFromJson($source, $codename)
    {
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if (!\Illuminate\Support\Facades\File::exists($filePath)) return '';
        $data = json_decode(\Illuminate\Support\Facades\File::get($filePath), true);

        return $this->searchNameRecursive($data, $codename);
    }

    private function searchNameRecursive($items, $codename)
    {
        foreach ($items as $item) {
            if ($item['codename'] == $codename) {
                return $item['name'];
            }
            if (isset($item['districts'])) {
                $res = $this->searchNameRecursive($item['districts'], $codename);
                if ($res) return $res;
            }
            if (isset($item['wards'])) {
                $res = $this->searchNameRecursive($item['wards'], $codename);
                if ($res) return $res;
            }
        }
        return null;
    }
}
