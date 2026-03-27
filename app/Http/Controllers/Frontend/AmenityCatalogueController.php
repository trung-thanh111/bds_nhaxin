<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Amenity\AmenityCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\Amenity\AmenityCatalogueService;
use App\Services\V1\Core\WidgetService;
use App\Models\Attribute;
use App\Repositories\RealEstate\AgentRepo;

class AmenityCatalogueController extends FrontendController
{
    protected $amenityCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $agentRepo;
    protected $amenityCatalogueService;
    protected $widgetService;

    public function __construct(
        AmenityCatalogueRepository $amenityCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        AgentRepo $agentRepo,
        AmenityCatalogueService $amenityCatalogueService,
        WidgetService $widgetService
    ) {
        parent::__construct();
        $this->amenityCatalogueRepository = $amenityCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->agentRepo = $agentRepo;
        $this->amenityCatalogueService = $amenityCatalogueService;
        $this->widgetService = $widgetService;
    }

    public function index($id, Request $request, $page = 1)
    {
        $amenityCatalogue = $this->amenityCatalogueRepository->getAmenityCatalogueById($id, $this->language);
        $breadcrumb = $this->amenityCatalogueRepository->breadcrumb($amenityCatalogue, $this->language);

        $priceField = $request->input('transaction_type') == '75' ? 'price_rent' : 'price_sale';
        $sorts = [
            'id:desc' => 'Mặc định',
            $priceField . ':asc' => 'Giá thấp đến cao',
            $priceField . ':desc' => 'Giá cao đến thấp',
            'area:asc' => 'Diện tích nhỏ đến lớn',
            'area:desc' => 'Diện tích lớn đến nhỏ',
        ];

        $sort = ['real_estates.id', 'DESC'];
        if ($request->filled('sort')) {
            $sortArr = explode(':', $request->input('sort'));
            if (count($sortArr) == 2) {
                $sort = ['real_estates.' . $sortArr[0], $sortArr[1]];
            }
        }

        $realEstates = $this->realEstateService->paginate(
            $request,
            $this->language,
            null,
            $page,
            ['path' => $amenityCatalogue->canonical],
            $sort
        );

        $attributeIds = [];
        foreach ($realEstates as $re) {
            $attributeIds[] = $re->price_unit;
            $attributeIds[] = $re->transaction_type;
            $attributeIds[] = $re->house_direction;
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
                'html' => view('frontend.realestate.catalogue.listing_results', compact('realEstates', 'attributeMap'))->render(),
                'total' => number_format($realEstates->total(), 0, ',', '.'),
                'sortLabel' => $sorts[$request->input('sort')] ?? 'Mặc định'
            ]);
        }

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
            ['keyword' => 'product-category', 'children' => true],
        ], $this->language);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $system = $this->system;
        $seo = seo($amenityCatalogue, $page);
        $config = $this->config();

        $template = 'frontend.realestate.catalogue.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'amenityCatalogue',
            'realEstates',
            'widgets',
            'agent',
            'attributeMap',
            'sorts'
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
