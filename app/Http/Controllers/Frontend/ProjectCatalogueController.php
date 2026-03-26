<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
use App\Services\V1\RealEstate\ProjectService;
use App\Services\V1\Core\WidgetService;
use App\Repositories\RealEstate\AgentRepo;

class ProjectCatalogueController extends FrontendController
{
    protected $projectCatalogueRepository;
    protected $projectRepository;
    protected $projectService;
    protected $widgetService;
    protected $agentRepo;
    protected $attributeRepository;
    protected $realEstateCatalogueRepository;

    protected $amenityRepository;

    public function __construct(
        ProjectCatalogueRepository $projectCatalogueRepository,
        ProjectRepository $projectRepository,
        ProjectService $projectService,
        WidgetService $widgetService,
        AgentRepo $agentRepo,
        \App\Repositories\Attribute\AttributeRepository $attributeRepository,
        \App\Repositories\RealEstate\RealEstateCatalogueRepository $realEstateCatalogueRepository,
        \App\Repositories\Amenity\AmenityRepository $amenityRepository
    ) {
        parent::__construct();
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->projectRepository = $projectRepository;
        $this->projectService = $projectService;
        $this->widgetService = $widgetService;
        $this->agentRepo = $agentRepo;
        $this->attributeRepository = $attributeRepository;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->amenityRepository = $amenityRepository;
    }

    public function index($id, Request $request, $page = 1)
    {
        $projectCatalogue = $this->projectCatalogueRepository->getProjectCatalogueById($id, $this->language);
        if (!$projectCatalogue) {
            abort(404);
        }

        $breadcrumb = $this->projectCatalogueRepository->breadcrumb($projectCatalogue, $this->language);
        
        // Merge project_catalogue_id into request for service paginate logic
        $request->merge(['project_catalogue_id' => $id]);

        $projects = $this->projectService->paginate($request, $this->language);

        // Filter Data
        $propertyTypes = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function($q) { $q->where('language_id', $this->language); }]); 
        $houseDirections = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 3],
            ['publish', '=', 2]
        ], true, ['languages' => function($q) { $q->where('language_id', $this->language); }], ['id', 'asc']);
        
        $furnitures = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 2],
            ['publish', '=', 2]
        ], true, ['languages' => function($q) { $q->where('language_id', $this->language); }], ['id', 'asc']);

        $balconyDirections = $this->attributeRepository->findByCondition([
            ['attribute_catalogue_id', '=', 4],
            ['publish', '=', 2]
        ], true, ['languages' => function($q) { $q->where('language_id', $this->language); }], ['id', 'asc']);

        $amenities = $this->amenityRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function($q) { $q->where('language_id', $this->language); }], ['order', 'asc']);


        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
            ['keyword' => 'product-category', 'children' => true],
        ], $this->language);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $system = $this->system;
        $seo = seo($projectCatalogue, $page);
        $config = $this->config();

        $template = 'frontend.project.catalogue.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'projectCatalogue',
            'projects',
            'widgets',
            'agent',
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
