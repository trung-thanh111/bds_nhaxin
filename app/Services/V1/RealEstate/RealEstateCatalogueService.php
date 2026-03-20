<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\Core\RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class RealEstateCatalogueService
 * @package App\Services
 */
class RealEstateCatalogueService extends BaseService
{
    protected $realEstateCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $controllerName = 'RealEstateCatalogueController';

    public function __construct(
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        RouterRepository $routerRepository,
    ){
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->routerRepository = $routerRepository;
    }

    public function paginate($request, $languageId){
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId]
            ]
        ];
        $realEstateCatalogues = $this->realEstateCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'real/estate/catalogue/index'],  
            ['real_estate_catalogues.lft', 'ASC'],
            [
                ['real_estate_catalogue_language as tb2','tb2.real_estate_catalogue_id', '=' , 'real_estate_catalogues.id']
            ], 
            ['languages']
        );

        return $realEstateCatalogues;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $realEstateCatalogue = $this->createCatalogue($request);
            if($realEstateCatalogue->id > 0){
                $this->updateLanguageForCatalogue($realEstateCatalogue, $request, $languageId);
                $this->createRouter($realEstateCatalogue, $request, $this->controllerName, $languageId);
                $this->nestedset = new Nestedsetbie([
                    'table' => 'real_estate_catalogues',
                    'foreignkey' => 'real_estate_catalogue_id',
                    'language_id' =>  $languageId ,
                    'join' => 'real_estate',
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    public function update($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $realEstateCatalogue = $this->realEstateCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($realEstateCatalogue, $request);
            if($flag == TRUE){
                $this->updateLanguageForCatalogue($realEstateCatalogue, $request, $languageId);
                $this->updateRouter(
                    $realEstateCatalogue, $request, $this->controllerName, $languageId
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'real_estate_catalogues',
                    'foreignkey' => 'real_estate_catalogue_id',
                    'language_id' =>  $languageId ,
                    'join' => 'real_estate',
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    public function destroy($id, $languageId){
        DB::beginTransaction();
        try{
            $realEstateCatalogue = $this->realEstateCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\RealEstateCatalogueController'],
            ]);

            $this->nestedset = new Nestedsetbie([
                'table' => 'real_estate_catalogues',
                'foreignkey' => 'real_estate_catalogue_id',
                'language_id' =>  $languageId ,
                'join' => 'real_estate',
            ]);
            $this->nestedset();

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    private function createCatalogue($request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id();
        $realEstateCatalogue = $this->realEstateCatalogueRepository->create($payload);
        return $realEstateCatalogue;
    }

    private function updateCatalogue($realEstateCatalogue, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->realEstateCatalogueRepository->update($realEstateCatalogue->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue($realEstateCatalogue, $request, $languageId){
        $payload = $this->formatLanguagePayload($realEstateCatalogue, $request, $languageId);
        $realEstateCatalogue->languages()->detach([$languageId, $realEstateCatalogue->id]);
        $language = $this->realEstateCatalogueRepository->createPivot($realEstateCatalogue, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($realEstateCatalogue, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['real_estate_catalogue_id'] = $realEstateCatalogue->id;
        return $payload;
    }

    private function paginateSelect(){
        return [
            'real_estate_catalogues.id', 
            'real_estate_catalogues.publish',
            'real_estate_catalogues.image',
            'real_estate_catalogues.level',
            'real_estate_catalogues.order',
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    private function payload(){
        return [
            'parent_id',
            'follow',
            'publish',
            'image',
            'album',
            'short_name'
        ];
    }

    private function payloadLanguage(){
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
