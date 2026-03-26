<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\Core\WidgetService;
use App\Repositories\RealEstate\AgentRepo;
use App\Models\Attribute;

class RealEstateCatalogueController extends FrontendController
{
    protected $realEstateCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $widgetService;
    protected $agentRepo;
    protected $attributeRepository;

    protected $amenityRepository;

    public function __construct(
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        WidgetService $widgetService,
        AgentRepo $agentRepo,
        \App\Repositories\Attribute\AttributeRepository $attributeRepository,
        \App\Repositories\Amenity\AmenityRepository $amenityRepository
    ) {
        parent::__construct();
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->widgetService = $widgetService;
        $this->agentRepo = $agentRepo;
        $this->attributeRepository = $attributeRepository;
        $this->amenityRepository = $amenityRepository;
    }

    public function index($id, $request, $page = 1)
    {
        $realEstateCatalogue = $this->realEstateCatalogueRepository->getRealEstateCatalogueById($id, $this->language);
        if (!$realEstateCatalogue) {
            abort(404);
        }

        $breadcrumb = $this->realEstateCatalogueRepository->breadcrumb($realEstateCatalogue, $this->language);

        // Filter Data
        $propertyTypes = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }]);
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


        // Sorting
        $sort = ['real_estates.id', 'DESC'];
        if ($request->has('sort')) {
            $sortArr = explode(':', $request->input('sort'));
            if (count($sortArr) == 2) {
                $sort = ['real_estates.' . $sortArr[0], $sortArr[1]];
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

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
            ['keyword' => 'product-category', 'children' => true],
        ], $this->language);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $system = $this->system;
        $seo = seo($realEstateCatalogue, $page);
        $config = $this->config();

        $template = 'frontend.realestate.catalogue.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'realEstateCatalogue',
            'realEstates',
            'widgets',
            'agent',
            'attributeMap',
            'propertyTypes',
            'houseDirections',
            'furnitures',
            'balconyDirections',
            'amenities',
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
            ],
        ];
    }
}
