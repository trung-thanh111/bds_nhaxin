<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use App\Services\V1\RealEstate\ProjectCatalogueService;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Http\Requests\RealEstate\StoreProjectCatalogueRequest;
// use App\Http\Requests\RealEstate\UpdateProjectCatalogueRequest;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;

class ProjectCatalogueController extends Controller
{
    protected $projectCatalogueService;
    protected $projectCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        ProjectCatalogueService $projectCatalogueService,
        ProjectCatalogueRepository $projectCatalogueRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = \App\Models\Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->projectCatalogueService = $projectCatalogueService;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'project_catalogues',
            'foreignkey' => 'project_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'project.catalogue.index');
        $projectCatalogues = $this->projectCatalogueService->paginate($request, $this->language);
        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'ProjectCatalogue',
            'seo' => __('messages.project_catalogue')
        ];
        $template = 'backend.realestate.project_catalogue.index';
        return view('backend.dashboard.layout', compact('projectCatalogues', 'config', 'template'));
    }

    public function create()
    {
        $this->authorize('modules', 'project.catalogue.create');
        $config = $this->configData();
        $config['method'] = 'create';
        $config['seo'] = __('messages.project_catalogue');
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.realestate.project_catalogue.store';
        return view('backend.dashboard.layout', compact('config', 'template', 'dropdown'));
    }

    public function store(StoreProjectCatalogueRequest $request)
    {
        if ($this->projectCatalogueService->create($request, $this->language)) {
            return redirect()->route('project.catalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->route('project.catalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'project.catalogue.edit');
        $projectCatalogue = $this->projectCatalogueRepository->getProjectCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'edit';
        $config['seo'] = __('messages.project_catalogue');
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.realestate.project_catalogue.store';
        return view('backend.dashboard.layout', compact('config', 'projectCatalogue', 'template', 'dropdown'));
    }

    public function update($id, StoreProjectCatalogueRequest $request)
    {
        if ($this->projectCatalogueService->update($id, $request, $this->language)) {
            return redirect()->route('project.catalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->route('project.catalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'project.catalogue.delete');
        $projectCatalogue = $this->projectCatalogueRepository->getProjectCatalogueById($id, $this->language);
        $config['seo'] = __('messages.project_catalogue');
        $template = 'backend.realestate.project_catalogue.delete';
        return view('backend.dashboard.layout', compact('projectCatalogue', 'template', 'config'));
    }

    public function destroy($id)
    {
        if ($this->projectCatalogueService->destroy($id)) {
            return redirect()->route('project.catalogue.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('project.catalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
        ];
    }
}
