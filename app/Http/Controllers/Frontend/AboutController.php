<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Services\V2\Impl\RealEstate\PropertyFacilityService;
use App\Services\V2\Impl\RealEstate\FloorplanService;
use App\Services\V2\Impl\RealEstate\GalleryService;
use App\Services\V2\Impl\RealEstate\LocationHighlightService;
use App\Services\V2\Impl\RealEstate\AgentService;
use Illuminate\Http\Request;

class AboutController extends FrontendController
{
    protected $propertyService;
    protected $agentService;
    protected $facilityService;
    protected $floorplanService;
    protected $locationHighlightService;
    protected $galleryService;

    public function __construct(
        PropertyService $propertyService,
        AgentService $agentService,
        PropertyFacilityService $facilityService,
        FloorplanService $floorplanService,
        LocationHighlightService $locationHighlightService,
        GalleryService $galleryService
    ) {
        $this->propertyService = $propertyService;
        $this->agentService = $agentService;
        $this->facilityService = $facilityService;
        $this->floorplanService = $floorplanService;
        $this->locationHighlightService = $locationHighlightService;
        $this->galleryService = $galleryService;
        parent::__construct();
    }

    /**
     * About page
     */
    public function index()
    {
        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);

        $agents = $this->agentService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true
        );

        $facilities = $this->facilityService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );

        $floorplans = $this->floorplanService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            relation: ['rooms'],
            orderBy: ['order', 'asc']
        );

        $locationHighlights = $this->locationHighlightService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );

        $galleries = $this->galleryService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );

        $primaryAgent = $this->agentService->findByCondition(
            condition: [['is_primary', '=', true], ['publish', '=', 2]]
        );

        $system = $this->system;
        $seo = $this->buildSeo('Tòa Nhà — ' . ($property->name ?? 'Linden Vietnam'));
        $schema = $this->schema($seo);
        $config = $this->config();

        return view('frontend.about.index', compact(
            'config',
            'seo',
            'system',
            'schema',
            'property',
            'agents',
            'facilities',
            'floorplans',
            'locationHighlights',
            'galleries',
            'primaryAgent',
        ));
    }

    // ------ Helpers ------

    private function buildSeo($title = null)
    {
        return [
            'meta_title' => $title ?? ($this->system['seo_meta_title'] ?? 'Guland'),
            'meta_keyword' => $this->system['seo_meta_keyword'] ?? '',
            'meta_description' => $this->system['seo_meta_description'] ?? '',
            'meta_image' => $this->system['seo_meta_images'] ?? '',
            'canonical' => config('app.url'),
        ];
    }

    public function schema(array $seo = []): string
    {
        return "<script type='application/ld+json'>
            {
                \"@context\": \"https://schema.org\",
                \"@type\": \"WebSite\",
                \"name\": \"" . ($seo['meta_title'] ?? '') . "\",
                \"url\": \"" . ($seo['canonical'] ?? '') . "\",
                \"description\": \"" . ($seo['meta_description'] ?? '') . "\"
            }
        </script>";
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'css' => [],
            'js' => []
        ];
    }
}
