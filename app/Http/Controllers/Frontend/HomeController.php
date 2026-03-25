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
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
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
    protected $realEstateRepository;
    protected $realEstateCatalogueRepository;
    protected $projectRepository;

    public function __construct(
        SystemRepository $systemRepository,
        PropertyService $propertyService,
        PropertyFacilityService $facilityService,
        FloorplanService $floorplanService,
        GalleryService $galleryService,
        LocationHighlightService $locationHighlightService,
        AgentService $agentService,
        SlideService $slideService,
        PostService $postService,
        RealEstateRepository $realEstateRepository,
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        ProjectRepository $projectRepository
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
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->projectRepository = $projectRepository;
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

        $projects = $this->projectRepository->findByCondition(
            condition: [config('apps.general.defaultPublish')],
            flag: true,
            relation: [
                'languages' => function($query) {
                    $query->where('language_id', $this->language);
                },
                'amenities.languages' => function($query) {
                    $query->where('language_id', $this->language);
                }
            ],
            orderBy: ['id', 'desc']
        )->take(9);

        // Fetch top-level categories and their 9 latest real estates
        $homepageCatalogues = $this->realEstateCatalogueRepository->findByCondition(
            [
                ['parent_id', '=', 0],
                config('apps.general.defaultPublish')
            ],
            true, // flag get()
            ['languages' => function($query) {
                $query->where('language_id', $this->language);
            }],
            ['order', 'desc']
        );

        if($homepageCatalogues){
            $attributeIds = [];
            foreach($homepageCatalogues as $key => $catalogue){
                // Get all children IDs for this catalogue using Nested Set (lft, rgt)
                $catIds = \Illuminate\Support\Facades\DB::table('real_estate_catalogues')
                    ->where('lft', '>=', $catalogue->lft)
                    ->where('rgt', '<=', $catalogue->rgt)
                    ->pluck('id')
                    ->toArray();

                $catalogue->real_estates = $this->realEstateRepository->findByCondition(
                    [
                        config('apps.general.defaultPublish')
                    ],
                    true,
                    [
                        'languages' => function($query) {
                            $query->where('language_id', $this->language);
                        },
                        'amenities.languages' => function($query) {
                            $query->where('language_id', $this->language);
                        }
                    ],
                    ['id', 'desc'],
                    ['whereIn' => $catIds, 'whereInField' => 'real_estate_catalogue_id']
                )->take(9);

                foreach($catalogue->real_estates as $re) {
                    if($re->transaction_type) $attributeIds[] = $re->transaction_type;
                    if($re->price_unit) $attributeIds[] = $re->price_unit;
                }
            }

            $attributeIds = array_unique(array_filter($attributeIds));
            $attributeMap = [];
            if(!empty($attributeIds)) {
                $attributeMap = \App\Models\Attribute::whereIn('id', $attributeIds)
                    ->with(['languages' => function($q) {
                        $q->where('language_id', $this->language);
                    }])
                    ->get()
                    ->pluck('languages.0.pivot.name', 'id')
                    ->toArray();
            }
        }

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
            'projects',
            'homepageCatalogues',
            'attributeMap'
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
