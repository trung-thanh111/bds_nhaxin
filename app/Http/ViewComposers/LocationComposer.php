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
        $realEstateCatalogues = $this->getCatalogues();
        $projectCatalogues = $this->getProjectCatalogues();
        
        $view->with('provinces', $provinces);
        $view->with('old_provinces', $old_provinces);
        $view->with('realEstateCatalogues', $realEstateCatalogues);
        $view->with('projectCatalogues', $projectCatalogues);
    }

    private function getCatalogues()
    {
        $publishCondition = [config('apps.general.defaultPublish')];
        
        return $this->realEstateCatalogueRepository->findByCondition(
            $publishCondition,
            true,
            ['languages' => function($query) {
                $query->where('language_id', $this->language);
            }],
            ['id', 'desc'],
            ['id', 'parent_id', 'lft', 'rgt']
        );
    }

    private function getProjectCatalogues()
    {
        return $this->projectCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
                ['parent_id', '=', 0],
            ],
            true,
            ['languages' => function($query) {
                $query->where('language_id', $this->language);
            }],
            ['lft', 'asc'],
            ['id', 'parent_id', 'lft', 'rgt']
        );
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
