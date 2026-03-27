<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\AgentRepo;

class ProjectController extends FrontendController
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

    public function index($id, Request $request)
    {
        $project = $this->projectRepository->getProjectById($id, $this->language);
        if (!$project) {
            abort(404);
        }

        $project->load(['catalogue.languages' => function($q) {
            $q->where('language_id', $this->language);
        }]);

        // Increment view_count
        try {
            $project->increment('view_count');
        } catch (\Exception $e) {
            // Fallback if column missing
        }

        // Build attributeMap for price units and other attributes (Main + Related)
        $attributeIds = [
            $project->price_unit,
            $project->legal_status,
            $project->status
        ];

        if (isset($relatedProjects) && count($relatedProjects)) {
            foreach ($relatedProjects as $related) {
                $attributeIds[] = $related->price_unit;
                $attributeIds[] = $related->legal_status;
                $attributeIds[] = $related->status;
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

        // Related Projects
        $relatedProjects = $this->projectRepository->pagination(
            ['*'],
            [
                'where' => [
                    ['id', '!=', $id],
                    ['project_catalogue_id', '=', $project->project_catalogue_id],
                    config('apps.general.defaultPublish')
                ]
            ],
            6,
            ['path' => $project->canonical . '.html'],
            ['id', 'DESC'],
            [],
            ['languages' => function($q) { $q->where('language_id', $this->language); }]
        );

        $config = $this->config();
        $system = $this->system;

        // SEO
        $seo = [
            'meta_title' => $project->meta_title ?? $project->name,
            'meta_description' => $project->meta_description ?? $project->description,
            'meta_keyword' => $project->meta_keyword,
            'canonical' => url($project->canonical . '.html'),
            'meta_image' => asset($project->image)
        ];

        return view('frontend.project.index', compact(
            'project',
            'agent',
            'seo',
            'realEstateCatalogues',
            'projectCatalogues',
            'relatedProjects',
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
