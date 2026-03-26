<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Attribute\AttributeCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\Attribute\AttributeCatalogueService;
use App\Services\V1\Core\WidgetService;
use App\Models\Attribute;
use App\Repositories\RealEstate\AgentRepo;

class AttributeCatalogueController extends FrontendController
{
    protected $attributeCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $agentRepo;
    protected $attributeCatalogueService;
    protected $widgetService;

    public function __construct(
        AttributeCatalogueRepository $attributeCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        AgentRepo $agentRepo,
        AttributeCatalogueService $attributeCatalogueService,
        WidgetService $widgetService
    ) {
        parent::__construct();
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->agentRepo = $agentRepo;
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->widgetService = $widgetService;
    }

    public function index($id, Request $request, $page = 1)
    {
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        if (!$attributeCatalogue) {
            abort(404);
        }

        $realEstates = $this->realEstateService->paginate(
            $request,
            $this->language,
            null,
            $page,
            ['path' => $attributeCatalogue->canonical]
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
        $seo = seo($attributeCatalogue, $page);
        $config = $this->config();

        $template = 'frontend.realestate.catalogue.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'attributeCatalogue',
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
