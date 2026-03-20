<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Services\V2\Impl\RealEstate\PropertyFacilityService;
use App\Services\V2\Impl\RealEstate\FloorplanService;
use App\Services\V2\Impl\RealEstate\GalleryService;
use App\Services\V2\Impl\RealEstate\LocationHighlightService;
use App\Services\V2\Impl\RealEstate\AgentService;
use App\Services\V1\Core\SlideService;
use App\Services\V1\Post\PostService;
use App\Repositories\Core\SystemRepository;
use Illuminate\Http\Request;

class HomeController extends FrontendController
{
    protected $systemRepository;
    protected $propertyService;
    protected $facilityService;
    protected $floorplanService;
    protected $galleryService;
    protected $locationHighlightService;
    protected $agentService;
    protected $slideService;
    protected $postService;

    public function __construct(
        SystemRepository $systemRepository,
        PropertyService $propertyService,
        PropertyFacilityService $facilityService,
        FloorplanService $floorplanService,
        GalleryService $galleryService,
        LocationHighlightService $locationHighlightService,
        AgentService $agentService,
        SlideService $slideService,
        PostService $postService
    ) {
        $this->systemRepository = $systemRepository;
        $this->propertyService = $propertyService;
        $this->facilityService = $facilityService;
        $this->floorplanService = $floorplanService;
        $this->galleryService = $galleryService;
        $this->locationHighlightService = $locationHighlightService;
        $this->agentService = $agentService;
        $this->slideService = $slideService;
        $this->postService = $postService;
        parent::__construct();
    }

    /**
     * Homepage — 9 sections
     */
    public function index()
    {
        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);

        $facilities = $this->facilityService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['sort_order', 'asc']
        );

        $floorplans = $this->floorplanService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            relation: ['rooms'],
            orderBy: ['order', 'asc']
        );

        $galleries = $this->galleryService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );

        $locationHighlights = $this->locationHighlightService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['sort_order', 'asc']
        );

        $primaryAgent = $this->agentService->findByCondition(
            condition: [['is_primary', '=', true], ['publish', '=', 2]]
        );

        $agents = $this->agentService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true
        );

        $slides = $this->slideService->getSlide(['main-slider']);
        $slides = $slides['main-slider'] ?? null;

        $posts = $this->postService->paginate(
            new Request(['publish' => 2]),
            $this->language,
            null,
            1
        );

        $system = $this->system;
        $seo = $this->buildSeo();
        $schema = $this->schema($seo);
        $config = $this->config();

        $template = 'frontend.homepage.home.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'schema',
            'property',
            'facilities',
            'floorplans',
            'galleries',
            'locationHighlights',
            'primaryAgent',
            'agents',
            'slides',
            'posts',
        ));
    }


    // ------ Helpers ------

    private function buildSeo($title = null)
    {
        return [
            'meta_title' => $title ?? ($this->system['seo_meta_title'] ?? 'HomePark'),
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
