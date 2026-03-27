<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\ProjectRepository;
use App\Services\V1\RealEstate\ProjectService;
use App\Services\V1\Core\WidgetService;
use App\Repositories\RealEstate\AgentRepo;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\User\ProvinceRepository;

class ProjectCatalogueController extends FrontendController
{
    protected $projectCatalogueRepository;
    protected $projectRepository;
    protected $projectService;
    protected $widgetService;
    protected $agentRepo;
    protected $attributeRepository;
    protected $realEstateCatalogueRepository;
    protected $realEstateRepository;

    protected $amenityRepository;
    protected $provinceRepository;

    public function __construct(
        ProjectCatalogueRepository $projectCatalogueRepository,
        ProjectRepository $projectRepository,
        ProjectService $projectService,
        WidgetService $widgetService,
        AgentRepo $agentRepo,
        \App\Repositories\Attribute\AttributeRepository $attributeRepository,
        \App\Repositories\RealEstate\RealEstateCatalogueRepository $realEstateCatalogueRepository,
        \App\Repositories\Amenity\AmenityRepository $amenityRepository,
        RealEstateRepository $realEstateRepository,
        ProvinceRepository $provinceRepository
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
        $this->realEstateRepository = $realEstateRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function index($id, Request $request, $page = 1)
    {
        $projectCatalogue = $this->projectCatalogueRepository->getProjectCatalogueById($id, $this->language);
        if (!$projectCatalogue) {
            abort(404);
        }

        $projectCataloguesRaw = $this->projectCatalogueRepository->breadcrumb($projectCatalogue, $this->language);
        $breadcrumb = [
            ['name' => 'Trang chủ', 'canonical' => url('/')]
        ];
        foreach ($projectCataloguesRaw as $cat) {
            $breadcrumb[] = [
                'name' => $cat->languages->first()->pivot->name,
                'canonical' => url($cat->languages->first()->pivot->canonical . '.html')
            ];
        }

        // Merge project_catalogue_id into request for service paginate logic
        $request->merge(['project_catalogue_id' => $id]);

        $currentSort = $request->input('sort') ?: 'id:desc';

        // Sorting logic for Projects
        $sort = ['projects.id', 'DESC'];
        if ($request->filled('sort')) {
            $sortArr = explode(':', $request->input('sort'));
            if (count($sortArr) == 2) {
                $sort = ['projects.' . $sortArr[0], $sortArr[1]];
            }
        }

        $projects = $this->projectService->paginate(
            $request,
            $this->language,
            $projectCatalogue,
            $page,
            ['path' => $projectCatalogue->canonical],
            $sort
        );

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

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
        ], $this->language);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $system = $this->system;
        $seo = seo($projectCatalogue, $page);
        $config = $this->config();

        $template = 'frontend.project.catalogue.index';

        // Sidebar Categories
        $projectCatalogues = $this->projectCatalogueRepository->findByCondition([
            ['publish', '=', 2],
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['lft', 'asc']);

        $provinces = $this->provinceRepository->all()->pluck('name', 'code');
        $old_provinces = $provinces;

        $newestRealEstates = $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'])->take(8);

        $featuredProjects = $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'])->take(8);

        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }]);

        $sorts = [
            'id:desc' => 'Mặc định',
            'area:desc' => 'Quy mô lớn đến nhỏ',
            'apartment_count:desc' => 'Nhiều căn hộ nhất',
            'created_at:desc' => 'Mới nhất'
        ];

        $isProject = true;

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.component.project_list', compact('projects'))->render(),
                'total' => $projects->total(),
                'sortLabel' => $sorts[$currentSort] ?? 'Mặc định'
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        }

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
            'newestRealEstates',
            'featuredProjects',
            'realEstateCatalogues',
            'projectCatalogues',
            'sorts',
            'isProject',
            'provinces',
            'old_provinces'
        ));
    }

    public function all(Request $request, $page = 1)
    {
        $projectCatalogue = null;
        $breadcrumb = [
            [
                'name' => 'Trang chủ',
                'canonical' => url('/')
            ],
            [
                'name' => 'Dự án',
                'canonical' => url('du-an.html')
            ]
        ];

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

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
        ], $this->language);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $system = $this->system;
        $config = $this->config();

        $template = 'frontend.project.catalogue.index';
        $currentSort = $request->input('sort') ?: 'id:desc';

        // Sorting logic
        $sort = ['projects.id', 'DESC'];
        if ($request->filled('sort')) {
            $sortArr = explode(':', $request->input('sort'));
            if (count($sortArr) == 2) {
                $sort = ['projects.' . $sortArr[0], $sortArr[1]];
            }
        }

        $projects = $this->projectService->paginate(
            $request,
            $this->language,
            null, // No catalogue
            $page,
            ['path' => 'du-an'],
            $sort
        );

        $seo = [
            'meta_title' => 'Dự án bất động sản - ' . ($system['seo_meta_title'] ?? ''),
            'meta_description' => 'Danh sách dự án bất động sản mới nhất, thông tin quy hoạch và đầu tư.',
            'canonical' => url('du-an.html'),
            'meta_image' => $system['seo_meta_image'] ?? '',
        ];

        // Sidebar Categories
        $projectCatalogues = $this->projectCatalogueRepository->findByCondition([
            ['publish', '=', 2],
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['lft', 'asc']);

        $provinces = $this->provinceRepository->all()->pluck('name', 'code');
        $old_provinces = $provinces;

        $newestRealEstates = $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'], [], [], 5);

        $featuredProjects = $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'], [], [], 5);

        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }]);

        $sorts = [
            'id:desc' => 'Mặc định',
            'area:desc' => 'Quy mô lớn đến nhỏ',
            'apartment_count:desc' => 'Nhiều căn hộ nhất',
            'created_at:desc' => 'Mới nhất'
        ];

        $isProject = true;

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.component.project_list', compact('projects'))->render(),
                'total' => $projects->total(),
                'sortLabel' => $sorts[$currentSort] ?? 'Mặc định'
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        }

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
            'newestRealEstates',
            'featuredProjects',
            'realEstateCatalogues',
            'projectCatalogues',
            'sorts',
            'isProject',
            'provinces',
            'old_provinces'
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
                'frontend/resources/library/js/filter.js',
            ],
        ];
    }
}
