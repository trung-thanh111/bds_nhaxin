<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Amenity\AmenityRepository;
use App\Repositories\Amenity\AmenityCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\Amenity\AmenityService;
use App\Services\V1\Core\WidgetService;
use App\Models\Attribute;
use App\Repositories\RealEstate\AgentRepo;

class AmenityController extends FrontendController
{
    protected $amenityRepository;
    protected $amenityCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $agentRepo;
    protected $amenityService;
    protected $widgetService;

    public function __construct(
        AmenityRepository $amenityRepository,
        AmenityCatalogueRepository $amenityCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        AgentRepo $agentRepo,
        AmenityService $amenityService,
        WidgetService $widgetService
    ) {
        parent::__construct();
        $this->amenityRepository = $amenityRepository;
        $this->amenityCatalogueRepository = $amenityCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->agentRepo = $agentRepo;
        $this->amenityService = $amenityService;
        $this->widgetService = $widgetService;
    }

    public function index($id, Request $request, $page = 1)
    {
        $amenity = $this->amenityRepository->getAmenityById($id, $this->language);
        if (!$amenity) {
            abort(404);
        }

        $breadcrumb = null;
        if ($amenity->amenity_catalogues) {
            $breadcrumb = $this->amenityCatalogueRepository->breadcrumb($amenity->amenity_catalogues, $this->language);
        }
        
        $realEstates = $this->realEstateService->paginate(
            $request,
            $this->language,
            null,
            $page,
            ['path' => $amenity->canonical]
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
                ->with(['languages' => function($q) {
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
        $seo = seo($amenity, $page);
        $config = $this->config();

        $template = 'frontend.realestate.catalogue.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'amenity',
            'realEstates',
            'widgets',
            'agent',
            'attributeMap'
        ));
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'css' => [],
            'js' => [],
        ];
    }
}
