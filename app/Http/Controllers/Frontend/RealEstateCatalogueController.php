<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\Core\WidgetService;
use App\Repositories\RealEstate\AgentRepo;
use App\Repositories\RealEstate\ProjectRepository;
use App\Models\Attribute;
use App\Repositories\RealEstate\ProjectCatalogueRepository;

class RealEstateCatalogueController extends FrontendController
{
    protected $realEstateCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $widgetService;
    protected $agentRepo;
    protected $attributeRepository;
    protected $projectRepository;
    protected $projectCatalogueRepository;
    protected $amenityRepository;

    public function __construct(
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        WidgetService $widgetService,
        AgentRepo $agentRepo,
        \App\Repositories\Attribute\AttributeRepository $attributeRepository,
        \App\Repositories\Amenity\AmenityRepository $amenityRepository,
        ProjectRepository $projectRepository,
        ProjectCatalogueRepository $projectCatalogueRepository
    ) {
        parent::__construct();
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->widgetService = $widgetService;
        $this->agentRepo = $agentRepo;
        $this->attributeRepository = $attributeRepository;
        $this->amenityRepository = $amenityRepository;
        $this->projectRepository = $projectRepository;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
    }

    public function index($id, Request $request, $page = 1)
    {
        $realEstateCatalogue = $this->realEstateCatalogueRepository->getRealEstateCatalogueById($id, $this->language);
        if (!$realEstateCatalogue) {
            abort(404);
        }

        $breadcrumb = $this->realEstateCatalogueRepository->breadcrumb($realEstateCatalogue, $this->language);

        // Identify Root ID (1: Sale, 16: Rent)
        $rootId = 1;
        $isRentalBranch = false;
        if ($id == 16) {
            $isRentalBranch = true;
            $rootId = 16;
        } else {
            foreach ($breadcrumb as $val) {
                if ($val->id == 16) {
                    $isRentalBranch = true;
                    $rootId = 16;
                    break;
                }
            }
        }

        if ($isRentalBranch) {
            $transactionType = '75';
            $request->merge(['transaction_type' => '75']);
        } else if (($id == 1 || $id == 0) && $request->input('transaction_type') == '75') {
            $transactionType = '75';
            $rootId = 16;
        } else {
            $transactionType = '74';
            $request->merge(['transaction_type' => '74']);
            $rootId = 1;
        }

        // Filter Property Types to only show children of current Root (Sale or Rent)
        $propertyTypes = $this->realEstateCatalogueRepository->findByCondition([
            ['parent_id', '=', $rootId],
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }]);

        $priceField = $request->input('transaction_type') == '75' ? 'price_rent' : 'price_sale';
        $sorts = [
            'id:desc' => 'Mặc định',
            $priceField . ':asc' => 'Giá thấp đến cao',
            $priceField . ':desc' => 'Giá cao đến thấp',
            'area:asc' => 'Diện tích nhỏ đến lớn',
            'area:desc' => 'Diện tích lớn đến nhỏ',
        ];

        $houseDirections = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 3],
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'asc']);

        $furnitures = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 2],
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'asc']);

        $balconyDirections = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 4],
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'asc']);

        $amenities = $this->amenityRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['order', 'asc']);

        // Sorting logic fix
        $sort = ['real_estates.id', 'DESC'];
        if ($request->filled('sort')) {
            $sortArr = explode(':', $request->input('sort'));
            if (count($sortArr) == 2) {
                $sortField = $sortArr[0];
                if ($sortField == 'price') {
                    $sortField = ($transactionType == '75') ? 'price_rent' : 'price_sale';
                }
                $sort = ['real_estates.' . $sortField, $sortArr[1]];
            }
        }

        $realEstates = $this->realEstateService->paginate(
            $request,
            $this->language,
            $realEstateCatalogue,
            $page,
            ['path' => $realEstateCatalogue->canonical],
            $sort
        );

        // Load necessary attributes for specs (Beds, Baths, Direction, etc.)
        $attributeIds = [];
        foreach ($realEstates as $re) {
            $attributeIds[] = $re->price_unit;
            $attributeIds[] = $re->transaction_type;
            $attributeIds[] = $re->house_direction;
            $attributeIds[] = $re->ownership_type;
            $attributeIds[] = $re->balcony_direction;
            $attributeIds[] = $re->interior;
            $attributeIds[] = $re->land_type;
            $attributeIds[] = $re->floor;
        }
        $attributeIds = array_unique(array_filter($attributeIds));

        $attributeMap = [];
        if (!empty($attributeIds)) {
            $attributeMap = Attribute::whereIn('id', $attributeIds)
                ->with(['languages' => function ($q) {
                    $q->where('language_id', $this->language);
                }])
                ->get()
                ->pluck('languages.0.pivot.name', 'id')
                ->toArray();
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.realestate.catalogue.listing_results', compact('realEstates', 'attributeMap', 'transactionType'))->render(),
                'total' => number_format($realEstates->total(), 0, ',', '.'),
                'sortLabel' => $sorts[$request->input('sort')] ?? 'Mặc định'
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
              ->header('Pragma', 'no-cache')
              ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        }

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
            ['keyword' => 'product-category', 'children' => true],
        ], $this->language);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $newestRealEstates = $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'], [], [], 5);

        $featuredProjects = $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'], [], [], 5);

        $system = $this->system;
        $seo = seo($realEstateCatalogue, $page);
        $config = $this->config();
        // Sidebar Categories
        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }]);

        $projectCatalogues = $this->projectCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }]);

        $isProject = false;

        return view('frontend.realestate.catalogue.index', compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'realEstateCatalogue',
            'realEstates',
            'propertyTypes',
            'houseDirections',
            'furnitures',
            'balconyDirections',
            'amenities',
            'attributeMap',
            'widgets',
            'agent',
            'newestRealEstates',
            'featuredProjects',
            'realEstateCatalogues',
            'projectCatalogues',
            'sorts',
            'isProject',
            'transactionType'
        ));
    }

    private function config()
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
}
