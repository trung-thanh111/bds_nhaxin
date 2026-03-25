<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use App\Services\V1\RealEstate\ProjectService;
use App\Repositories\RealEstate\ProjectRepository;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Language;
use App\Models\Province;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;
    protected $projectRepository;
    protected $language;

    public function __construct(
        ProjectService $projectService,
        ProjectRepository $projectRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });

        $this->projectService = $projectService;
        $this->projectRepository = $projectRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'project.index');
        $projects = $this->projectService->paginate($request, $this->language);
        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Project',
            'seo' => __('messages.project'),
            'method' => 'index'
        ];
        $template = 'backend.realestate.project.index';
        return view('backend.dashboard.layout', compact('projects', 'config', 'template'));
    }

    public function create()
    {
        $this->authorize('modules', 'project.create');
        $config = $this->configData();
        $config['method'] = 'create';
        $config['seo'] = __('messages.project');
        
        $dropdown = (new \App\Classes\Nestedsetbie([
            'table' => 'project_catalogues',
            'foreignkey' => 'project_catalogue_id',
            'language_id' => $this->language,
        ]))->Dropdown();
        
        $template = 'backend.realestate.project.store';
        $dropdowns = $this->getDropdowns();
        $provinces = $this->getProvincesFromJson('after');
        $old_provinces = $this->getProvincesFromJson('before');
        $amenityCatalogues = $this->getAmenityCatalogues();
        $floorplans = \App\Models\Floorplan::with(['languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->get();
        
        $allProjects = \App\Models\Project::with(['languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->get();
        
        return view('backend.dashboard.layout', compact('config', 'template', 'dropdown', 'dropdowns', 'provinces', 'old_provinces', 'amenityCatalogues', 'allProjects', 'floorplans'));
    }

    public function store(StoreProjectRequest $request)
    {
        if ($this->projectService->create($request, $this->language)) {
            return redirect()->route('project.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'project.edit');
        $project = $this->projectRepository->getProjectById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'edit';
        $config['seo'] = __('messages.project');
        
        $dropdown = (new \App\Classes\Nestedsetbie([
            'table' => 'project_catalogues',
            'foreignkey' => 'project_catalogue_id',
            'language_id' => $this->language,
        ]))->Dropdown();
        
        $template = 'backend.realestate.project.store';
        $dropdowns = $this->getDropdowns();
        $provinces = $this->getProvincesFromJson('after');
        $old_provinces = $this->getProvincesFromJson('before');
        $amenityCatalogues = $this->getAmenityCatalogues();
        $floorplans = \App\Models\Floorplan::with(['languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->get();
        
        $allProjects = \App\Models\Project::with(['languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->where('id', '!=', $id)->get();
        
        return view('backend.dashboard.layout', compact('config', 'project', 'template', 'dropdown', 'dropdowns', 'provinces', 'old_provinces', 'amenityCatalogues', 'allProjects', 'floorplans'));
    }

    public function update($id, UpdateProjectRequest $request)
    {
        if ($this->projectService->update($id, $request, $this->language)) {
            return redirect()->route('project.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'project.delete');
        $project = $this->projectRepository->getProjectById($id, $this->language);
        $config = $this->configData();
        $config['method'] = 'delete';
        $config['seo'] = __('messages.project');
        $template = 'backend.realestate.project.delete';
        return view('backend.dashboard.layout', compact('project', 'template', 'config'));
    }

    public function destroy($id)
    {
        if ($this->projectService->destroy($id)) {
            return redirect()->route('project.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function getDropdowns()
    {
        $codes = ['phap_ly'];
        $result = [];
        foreach ($codes as $code) {
            $catalogue = \App\Models\AttributeCatalogue::with(['attributes.languages' => function ($query) {
                $query->where('language_id', $this->language);
            }])->where('code', $code)->first();

            if ($catalogue) {
                $temp = [];
                foreach ($catalogue->attributes as $attr) {
                    $temp[$attr->id] = $attr->languages->first()->pivot->name ?? '';
                }
                $result[$code] = $temp;
            }
        }
        return $result;
    }

    private function getProvincesFromJson($source)
    {
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if (!\Illuminate\Support\Facades\File::exists($filePath)) {
            return [];
        }
        $data = json_decode(\Illuminate\Support\Facades\File::get($filePath), true);
        $result = [];
        foreach ($data as $item) {
            $result[$item['codename']] = $item['name'];
        }
        return $result;
    }

    private function getAmenityCatalogues()
    {
        return \App\Models\AmenityCatalogue::with(['amenities.languages' => function ($query) {
            $query->where('language_id', $this->language);
        }, 'languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->get();
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/location.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
        ];
    }
}
