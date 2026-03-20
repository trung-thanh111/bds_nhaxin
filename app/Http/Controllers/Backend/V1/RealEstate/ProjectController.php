<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use App\Services\V1\RealEstate\ProjectService;
use App\Repositories\RealEstate\ProjectRepository;
use App\Http\Requests\RealEstate\StoreProjectRequest;
use App\Http\Requests\RealEstate\UpdateProjectRequest;
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
        $realEstates = \App\Models\RealEstate::with(['languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->get();
        $dropdown = (new \App\Classes\Nestedsetbie([
            'table' => 'project_catalogues',
            'foreignkey' => 'project_catalogue_id',
            'language_id' => $this->language,
        ]))->Dropdown();
        $agents = $this->getAgentDropdown();
        $priceUnits = $this->getPriceUnits();
        $template = 'backend.realestate.project.store';
        return view('backend.dashboard.layout', compact('config', 'template', 'realEstates', 'dropdown', 'agents', 'priceUnits'));
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
        $realEstates = \App\Models\RealEstate::with(['languages' => function ($query) {
            $query->where('language_id', $this->language);
        }])->get();
        $dropdown = (new \App\Classes\Nestedsetbie([
            'table' => 'project_catalogues',
            'foreignkey' => 'project_catalogue_id',
            'language_id' => $this->language,
        ]))->Dropdown();
        $agents = $this->getAgentDropdown();
        $priceUnits = $this->getPriceUnits();
        $template = 'backend.realestate.project.store';
        return view('backend.dashboard.layout', compact('config', 'project', 'template', 'realEstates', 'dropdown', 'agents', 'priceUnits'));
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

    private function getAgentDropdown()
    {
        $agents = \App\Models\Agent::select(['id', 'full_name'])->get();
        $temp = [0 => '[Chọn Nhân Viên]'];
        foreach ($agents as $item) {
            $temp[$item->id] = $item->full_name;
        }
        return $temp;
    }

    private function getPriceUnits()
    {
        $catalogue = \App\Models\AttributeCatalogue::where('code', 'loai_gia')->first();
        if (!$catalogue) return [0 => '[Đơn vị giá]'];

        $priceUnits = \App\Models\Attribute::select(['attributes.id', 'tb2.name'])
            ->join('attribute_language as tb2', 'tb2.attribute_id', '=', 'attributes.id')
            ->where('attributes.attribute_catalogue_id', $catalogue->id)
            ->where('tb2.language_id', $this->language)
            ->get();

        $temp = [0 => '[Đơn vị giá]'];
        foreach ($priceUnits as $item) {
            $temp[$item->id] = $item->name;
        }
        return $temp;
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
