<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\AgentService;
use App\Services\V1\Post\PostService;
use App\Repositories\Core\SystemRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
use Illuminate\Http\Request;

class HomeController extends FrontendController
{
    protected $systemRepository;
    protected $agentService;
    protected $postService;
    protected $realEstateRepository;
    protected $realEstateCatalogueRepository;
    protected $projectRepository;

    public function __construct(
        SystemRepository $systemRepository,
        AgentService $agentService,
        PostService $postService,
        RealEstateRepository $realEstateRepository,
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        ProjectRepository $projectRepository
    ) {
        $this->systemRepository = $systemRepository;
        $this->agentService = $agentService;
        $this->postService = $postService;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->projectRepository = $projectRepository;
        parent::__construct();
    }

    /**
     * Homepage
     */
    public function index()
    {
        $homepageData = \Illuminate\Support\Facades\Cache::remember('homepage_complete_data_' . $this->language, 3600, function () {
            $data = [];

            $data['primaryAgent'] = $this->agentService->findByCondition(
                condition: [['publish', '=', 2], ['is_primary', '=', 1]],
                flag: false
            );

            $data['projects'] = $this->projectRepository->findByCondition(
                condition: [config('apps.general.defaultPublish')],
                flag: true,
                relation: [
                    'languages' => function ($query) {
                        $query->where('language_id', $this->language);
                    },
                    'amenities.languages' => function ($query) {
                        $query->where('language_id', $this->language);
                    }
                ],
                orderBy: ['id', 'desc']
            )->take(9);

            $catalogues = $this->realEstateCatalogueRepository->findByCondition(
                condition: [['parent_id', '=', 0], config('apps.general.defaultPublish')],
                flag: true,
                relation: ['languages' => function ($query) {
                    $query->where('language_id', $this->language);
                }],
                orderBy: ['order', 'desc']
            );

            if ($catalogues) {
                foreach ($catalogues as $catalogue) {
                    $catIds = \Illuminate\Support\Facades\DB::table('real_estate_catalogues')
                        ->where('lft', '>=', $catalogue->lft)
                        ->where('rgt', '<=', $catalogue->rgt)
                        ->pluck('id')->toArray();

                    $catalogue->real_estates = $this->realEstateRepository->findByCondition(
                        condition: [config('apps.general.defaultPublish')],
                        flag: true,
                        relation: [
                            'languages' => function ($query) {
                                $query->where('language_id', $this->language);
                            },
                            'amenities.languages' => function ($query) {
                                $query->where('language_id', $this->language);
                            }
                        ],
                        orderBy: ['id', 'desc'],
                        param: ['whereIn' => $catIds, 'whereInField' => 'real_estate_catalogue_id']
                    )->take(9);
                }
            }
            $data['homepageCatalogues'] = $catalogues;

            return $data;
        });

        $projects = $homepageData['projects'];
        $homepageCatalogues = $homepageData['homepageCatalogues'];
        $primaryAgent = $homepageData['primaryAgent'];

        // Cache latest posts
        $posts = \Illuminate\Support\Facades\Cache::remember('homepage_latest_posts_' . $this->language, 1800, function () {
            return $this->postService->paginate(
                new Request(['publish' => 2]),
                $this->language,
                null,
                1
            );
        });

        $attributeMap = [];
        if ($homepageCatalogues) {
            $attributeIds = [];
            foreach ($homepageCatalogues as $catalogue) {
                foreach ($catalogue->real_estates as $re) {
                    if ($re->transaction_type) $attributeIds[] = $re->transaction_type;
                    if ($re->price_unit) $attributeIds[] = $re->price_unit;
                    if ($re->house_direction) $attributeIds[] = $re->house_direction;
                    if ($re->ownership_type) $attributeIds[] = $re->ownership_type;
                    if ($re->balcony_direction) $attributeIds[] = $re->balcony_direction;
                    if ($re->interior) $attributeIds[] = $re->interior;
                    if ($re->land_type) $attributeIds[] = $re->land_type;
                    if ($re->floor) $attributeIds[] = $re->floor;
                }
            }

            $attributeIds = array_unique(array_filter($attributeIds));
            if (!empty($attributeIds)) {
                $attributeMap = \App\Models\Attribute::whereIn('id', $attributeIds)
                    ->with(['languages' => function ($q) {
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
            'primaryAgent',
            'posts',
            'projects',
            'homepageCatalogues',
            'attributeMap'
        ));
    }

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
