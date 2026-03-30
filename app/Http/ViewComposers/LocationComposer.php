<?php
namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use Illuminate\Support\Facades\Log;

class LocationComposer
{
    protected $realEstateCatalogueRepository;
    protected $projectCatalogueRepository;
    protected $language;

    protected static $cachedRealEstateCatalogues = null;
    protected static $cachedProjectCatalogues = null;

    public function __construct(
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        ProjectCatalogueRepository $projectCatalogueRepository,
        $language
    ){
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->language = $language;
    }

    public function compose(\Illuminate\View\View $view)
    {
        $provinces = $this->getProvinces('after');
        $old_provinces = $this->getProvinces('before');
        
        if (static::$cachedRealEstateCatalogues === null) {
            static::$cachedRealEstateCatalogues = $this->getCatalogues();
        }
        
        if (static::$cachedProjectCatalogues === null) {
            static::$cachedProjectCatalogues = $this->getProjectCatalogues();
        }
        
        $view->with('provinces', $provinces);
        $view->with('old_provinces', $old_provinces);
        $view->with('realEstateCatalogues', static::$cachedRealEstateCatalogues);
        $view->with('projectCatalogues', static::$cachedProjectCatalogues);
    }

    private function getCatalogues()
    {
        return \Illuminate\Support\Facades\Cache::remember('global_real_estate_catalogues_' . $this->language, 3600, function() {
            $publishCondition = [config('apps.general.defaultPublish')];
            return $this->realEstateCatalogueRepository->findByCondition(
                $publishCondition,
                true,
                ['languages' => function($query) {
                    $query->where('language_id', $this->language);
                }],
                ['id', 'desc']
            );
        });
    }

    private function getProjectCatalogues()
    {
        return \Illuminate\Support\Facades\Cache::remember('global_project_catalogues_' . $this->language, 3600, function() {
            return $this->projectCatalogueRepository->findByCondition(
                [
                    ['publish', '=', 2],
                ],
                true,
                ['languages' => function($query) {
                    $query->where('language_id', $this->language);
                }],
                ['lft', 'asc']
            );
        });
    }

    private function getProvinces(string $source): array
    {
        $cacheKey = 'provinces_list_' . $source;
        return Cache::remember($cacheKey, 3600, function () use ($source) {
            $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
            if (!File::exists($filePath)) return [];
            
            $data = json_decode(File::get($filePath), true) ?? [];
            $provinces = [];
            foreach ($data as $item) {
                if (isset($item['codename']) && isset($item['name'])) {
                    $provinces[$item['codename']] = $item['name'];
                }
            }
            return $provinces;
        });
    }
}
