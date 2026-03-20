<?php

namespace App\Services\V1\Amenity;

use App\Services\V1\BaseService;
use App\Repositories\Amenity\AmenityCatalogueRepository;
use App\Repositories\Core\RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Class AmenityCatalogueService
 * @package App\Services\V1\Amenity
 */
class AmenityCatalogueService extends BaseService
{
    protected $amenityCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $controllerName = 'AmenityCatalogueController';

    public function __construct(
        AmenityCatalogueRepository $amenityCatalogueRepository,
        RouterRepository $routerRepository
    ) {
        $this->amenityCatalogueRepository = $amenityCatalogueRepository;
        $this->routerRepository = $routerRepository;
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId]
            ]
        ];
        $amenityCatalogues = $this->amenityCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perPage,
            ['path' => 'amenity/catalogue/index'],
            ['amenity_catalogues.lft', 'ASC'],
            [
                ['amenity_catalogue_language as tb2', 'tb2.amenity_catalogue_id', '=', 'amenity_catalogues.id']
            ],
            ['languages']
        );

        return $amenityCatalogues;
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $amenityCatalogue = $this->createCatalogue($request);
            if ($amenityCatalogue->id > 0) {
                $this->updateLanguageForCatalogue($amenityCatalogue, $request, $languageId);
                $this->createRouter($amenityCatalogue, $request, $this->controllerName, $languageId);
                $this->nestedset = new Nestedsetbie([
                    'table' => 'amenity_catalogues',
                    'foreignkey' => 'amenity_catalogue_id',
                    'language_id' =>  $languageId,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $amenityCatalogue = $this->amenityCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($amenityCatalogue, $request);
            if ($flag == TRUE) {
                $this->updateLanguageForCatalogue($amenityCatalogue, $request, $languageId);
                $this->updateRouter(
                    $amenityCatalogue,
                    $request,
                    $this->controllerName,
                    $languageId
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'amenity_catalogues',
                    'foreignkey' => 'amenity_catalogue_id',
                    'language_id' =>  $languageId,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function destroy($id, $languageId)
    {
        DB::beginTransaction();
        try {
            $amenityCatalogue = $this->amenityCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\AmenityCatalogueController'],
            ]);
            $this->nestedset = new Nestedsetbie([
                'table' => 'amenity_catalogues',
                'foreignkey' => 'amenity_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function createCatalogue($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $amenityCatalogue = $this->amenityCatalogueRepository->create($payload);
        return $amenityCatalogue;
    }

    private function updateCatalogue($amenityCatalogue, $request)
    {
        $payload = $request->only($this->payload());
        $flag = $this->amenityCatalogueRepository->update($amenityCatalogue->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue($amenityCatalogue, $request, $languageId)
    {
        $payload = $this->formatLanguagePayload($amenityCatalogue, $request, $languageId);
        $amenityCatalogue->languages()->detach([$languageId, $amenityCatalogue->id]);
        $language = $this->amenityCatalogueRepository->createPivot($amenityCatalogue, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($amenityCatalogue, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['amenity_catalogue_id'] = $amenityCatalogue->id;
        return $payload;
    }

    private function paginateSelect()
    {
        return [
            'amenity_catalogues.id',
            'amenity_catalogues.publish',
            'amenity_catalogues.image',
            'amenity_catalogues.level',
            'amenity_catalogues.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }

    private function payload()
    {
        return [
            'parent_id',
            'publish',
            'image',
            'icon',
            'order',
        ];
    }

    private function payloadLanguage()
    {
        return [
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        ];
    }
}
