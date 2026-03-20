<?php

namespace App\Http\Controllers\Backend\V2\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RealEstate\Agent\StoreRequest;
use App\Http\Requests\RealEstate\Agent\UpdateRequest;
use App\Services\V2\Impl\RealEstate\AgentService;
use App\Models\Language;

class AgentController extends Controller
{

    private $service;

    protected $language;

    public function __construct(
        AgentService $service
    ) {
        $this->service = $service;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'agent.index');
        $records = $this->service->pagination($request);
        $config = [
            ...$this->config(),
            'extendJs' => true
        ];
        $template = 'backend.agent.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'records'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'agent.create');
        $config = [
            ...$this->config(),
            'method' => 'create',
            'extendJs' => true
        ];
        $template = 'backend.agent.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function edit($id)
    {
        $this->authorize('modules', 'agent.update');
        if (!$record = $this->service->findById($id)) {
            return redirect()->route('agent.index')->with('error', 'Bản ghi không tồn tại');
        }
        $config = [
            ...$this->config(),
            'method' => 'update',
            'extendJs' => true
        ];
        $template = 'backend.agent.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('modules', 'agent.create');
        $response = $this->service->save($request, 'store');
        return $this->handleActionResponse($response, $request, redirectRoute: 'agent.index');
    }


    public function update($id, UpdateRequest $request)
    {
        $this->authorize('modules', 'agent.update');
        $response = $this->service->save($request, 'update', $id);
        return $this->handleActionResponse($response, $request, redirectRoute: 'agent.index');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'agent.destroy');
        $record = $this->service->findById($id);
        $this->checkExists($record);
        $config = [
            ...$this->config(),
            'method' => 'update'
        ];
        $template = 'backend.agent.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'record'
        ));
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('modules', 'agent.destroy');
        $response = $this->service->destroy($id);
        return $this->handleActionResponse($response, $request, message: 'Xóa bản ghi thành công', redirectRoute: 'agent.index');
    }

    private function config(): array
    {
        return $config = [
            'model' => 'Agent',
            'seo' => $this->seo()
        ];
    }

    private function seo()
    {
        return [
            'index' => [
                'title' => 'Quản lý Nhân Viên Môi Giới',
                'table' => 'Danh sách Nhân Viên Môi Giới'
            ],
            'create' => [
                'title' => 'Thêm mới Nhân Viên Môi Giới'
            ],
            'update' => [
                'title' => 'Cập nhật Nhân Viên Môi Giới'
            ],
            'delete' => [
                'title' => 'Xóa Nhân Viên Môi Giới'
            ]
        ];
    }
}
