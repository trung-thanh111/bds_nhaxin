<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\RealEstate\RealEstateCatalogueService;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Http\Requests\RealEstate\StoreRealEstateCatalogueRequest;
use App\Http\Requests\RealEstate\UpdateRealEstateCatalogueRequest;
use App\Http\Requests\RealEstate\DeleteRealEstateCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class RealEstateCatalogueController extends Controller
{
    protected $realEstateCatalogueService;
    protected $realEstateCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        RealEstateCatalogueService $realEstateCatalogueService,
        RealEstateCatalogueRepository $realEstateCatalogueRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->realEstateCatalogueService = $realEstateCatalogueService;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'real_estate_catalogues',
            'foreignkey' => 'real_estate_catalogue_id',
            'language_id' =>  $this->language,
            'join' => 'real_estate',
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'real_estate.catalogue.index');
        $realEstateCatalogues = $this->realEstateCatalogueService->paginate($request, $this->language);
        $config = [
            'extendJs' => true,
            'model' => 'RealEstateCatalogue',
        ];
        $config['seo'] = __('messages.realEstateCatalogue');
        $template = 'backend.realestate.catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'realEstateCatalogues'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'real_estate.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.realEstateCatalogue');
        $config['method'] = 'create';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.realestate.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
        ));
    }

    public function store(StoreRealEstateCatalogueRequest $request)
    {
        $success = $this->realEstateCatalogueService->create($request, $this->language);

        if ($success) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Thêm mới bản ghi thành công');
            }
            return redirect()->route('real_estate.catalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'real_estate.catalogue.update');
        $realEstateCatalogue = $this->realEstateCatalogueRepository->getRealEstateCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.realEstateCatalogue');
        $config['method'] = 'edit';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.realestate.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'realEstateCatalogue',
        ));
    }

    public function update($id, UpdateRealEstateCatalogueRequest $request)
    {
        $queryString = base64_decode($request->getQueryString());

        if ($this->realEstateCatalogueService->update($id, $request, $this->language)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()
                    ->route('real_estate.catalogue.edit', [$id, 'query' => base64_encode($queryString)])
                    ->with('success', 'Cập nhật bản ghi thành công');
            }

            return redirect()
                ->route('real_estate.catalogue.index', $queryString)
                ->with('success', 'Cập nhật bản ghi thành công');
        }

        return redirect()
            ->back()
            ->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'real_estate.catalogue.destroy');
        $config['seo'] = __('messages.realEstateCatalogue');
        $realEstateCatalogue = $this->realEstateCatalogueRepository->getRealEstateCatalogueById($id, $this->language);
        $template = 'backend.realestate.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'realEstateCatalogue',
            'config',
        ));
    }

    public function destroy(DeleteRealEstateCatalogueRequest $request, $id)
    {
        if ($this->realEstateCatalogueService->destroy($id, $this->language)) {
            return redirect()->route('real_estate.catalogue.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('real_estate.catalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData()
    {
        return [
            'extendJs' => true
        ];
    }
}
