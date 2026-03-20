<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\ContactRequest\StoreRequest;
use App\Http\Requests\RealEstate\ContactRequest\UpdateRequest;
use App\Services\V2\Impl\RealEstate\ContactRequestService;
use App\Models\Project;
use App\Services\V2\Impl\RealEstate\AgentService;
use App\Models\Language;

class ContactRequestController extends Controller
{

    private $service;
    protected $agentService;
    protected $language;

    public function __construct(
        ContactRequestService $service,
        AgentService $agentService
    ) {
        $this->service = $service;
        $this->agentService = $agentService;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'contact_request.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.contact_request.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'contact_request.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $projects = Project::with(['languages' => function($query) {
            $query->where('language_id', $this->language);
        }])->get();
        $agents = $this->agentService->all();
        $template = 'backend.contact_request.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'projects',
            'agents'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'contact_request.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('contact_request.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $projects = Project::with(['languages' => function($query) {
            $query->where('language_id', $this->language);
        }])->get();
        $agents = $this->agentService->all();
        $template = 'backend.contact_request.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record',
            'projects',
            'agents'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'contact_request.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'contact_request.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'contact_request.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'contact_request.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'contact_request.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.contact_request.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'contact_request.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'contact_request.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'ContactRequest',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Liên Hệ',
                'table' => 'Danh sách Liên Hệ Khách Hàng'
            ],
            'create' => [
                'title' => 'Thêm mới Liên Hệ'
            ],
            'update' => [
                'title' => 'Cập nhật Liên Hệ'
            ],
            'delete' => [
                'title' => 'Xóa Liên Hệ'
            ]
        ];
    }
}
