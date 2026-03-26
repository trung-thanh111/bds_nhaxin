<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\AgentRepo;

class RealEstateController extends FrontendController
{
    protected $realEstateRepository;
    protected $realEstateCatalogueRepository;
    protected $projectRepository;
    protected $projectCatalogueRepository;
    protected $agentRepo;

    public function __construct(
        RealEstateRepository $realEstateRepository,
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        ProjectRepository $projectRepository,
        ProjectCatalogueRepository $projectCatalogueRepository,
        AgentRepo $agentRepo
    ) {
        parent::__construct();
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->projectRepository = $projectRepository;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->agentRepo = $agentRepo;
    }

    public function index($id, $request)
    {
        $realEstate = $this->realEstateRepository->getRealEstateById($id, $this->language);
        if (!$realEstate) {
            abort(404);
        }

        $realEstate->load(['catalogue.languages' => function($q) {
            $q->where('language_id', $this->language);
        }]);

        // Build attributeMap for all relevant attributes (Main + Related)
        $attributeIds = [
            $realEstate->transaction_type, 
            $realEstate->price_unit,
            $realEstate->house_direction,
            $realEstate->balcony_direction,
            $realEstate->ownership_type,
            $realEstate->interior,
            $realEstate->land_type
        ];

        if (isset($relatedRealEstates) && count($relatedRealEstates)) {
            foreach ($relatedRealEstates as $related) {
                $attributeIds[] = $related->transaction_type;
                $attributeIds[] = $related->price_unit;
                $attributeIds[] = $related->house_direction;
                // Add more if needed for the cards
            }
        }

        $attributeIds = array_unique(array_filter($attributeIds));
        
        $attributeMap = [];
        if (!empty($attributeIds)) {
            $attributeMap = \App\Models\Attribute::whereIn('id', $attributeIds)
                ->with(['languages' => function($q) {
                    $q->where('language_id', $this->language);
                }])
                ->get()
                ->pluck('languages.0.pivot.name', 'id')
                ->toArray();
        }

        // Get Primary Agent
        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        // Sidebar data: Get all catalogues
        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages' => function($q) {
            $q->where('language_id', $this->language);
        }]);

        $projectCatalogues = $this->projectCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages' => function($q) {
            $q->where('language_id', $this->language);
        }]);

        // Related Real Estates (same category)
        $relatedRealEstates = $this->realEstateRepository->pagination(
            ['*'],
            [
                'where' => [
                    ['id', '!=', $id],
                    ['real_estate_catalogue_id', '=', $realEstate->real_estate_catalogue_id],
                    config('apps.general.defaultPublish')
                ]
            ],
            6,
            ['path' => $realEstate->canonical . '.html'],
            ['id', 'DESC'],
            [],
            ['languages' => function($q) { $q->where('language_id', $this->language); }]
        );

        // SEO
        $seo = [
            'meta_title' => $realEstate->meta_title ?? $realEstate->name,
            'meta_description' => $realEstate->meta_description ?? $realEstate->description,
            'meta_keyword' => $realEstate->meta_keyword,
            'canonical' => url($realEstate->canonical . '.html'),
            'meta_image' => asset($realEstate->image)
        ];

        $config = $this->config();
        $system = $this->system;

        return view('frontend.realestate.index', compact(
            'realEstate',
            'agent',
            'seo',
            'realEstateCatalogues',
            'projectCatalogues',
            'relatedRealEstates',
            'attributeMap',
            'config',
            'system'
        ));
    }

    private function config()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/owl.carousel.min.js',
                'frontend/resources/library/js/carousel.js',
            ]
        ];
    }
}
