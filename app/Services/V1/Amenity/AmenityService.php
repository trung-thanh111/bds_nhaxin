<?php

namespace App\Services\V1\Amenity;

use App\Services\V1\BaseService;
use App\Repositories\Amenity\AmenityRepository;
use App\Repositories\Core\RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Class AmenityService
 * @package App\Services\V1\Amenity
 */
class AmenityService extends BaseService
{
    protected $amenityRepository;
    protected $routerRepository;
    protected $controllerName = 'AmenityController';

    public function __construct(
        AmenityRepository $amenityRepository,
        RouterRepository $routerRepository
    ){
        $this->amenityRepository = $amenityRepository;
        $this->routerRepository = $routerRepository;
    }

    public function paginate($request, $languageId){
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ]
        ];
        if($request->has('amenity_catalogue_id') && $request->input('amenity_catalogue_id') > 0){
            $condition['where'][] = ['amenities.amenity_catalogue_id', '=', $request->input('amenity_catalogue_id')];
        }

        $amenities = $this->amenityRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'amenity/index'],  
            ['amenities.order', 'ASC'],
            [
                ['amenity_language as tb2','tb2.amenity_id', '=' , 'amenities.id'],
                [DB::raw('(SELECT real_estate_catalogue_id, name, language_id FROM real_estate_catalogue_language WHERE language_id = '.$languageId.') as cat_lang'), 'cat_lang.real_estate_catalogue_id', '=', 'amenities.amenity_catalogue_id', 'left']
            ], 
            ['languages']
        );

        return $amenities;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $amenity = $this->createAmenity($request);
            if($amenity->id > 0){
                $this->updateLanguageForAmenity($amenity, $request, $languageId);
                $this->createRouter($amenity, $request, $this->controllerName, $languageId);
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $amenity = $this->amenityRepository->findById($id);
            $flag = $this->updateAmenity($amenity, $request);
            if($flag == TRUE){
                $this->updateLanguageForAmenity($amenity, $request, $languageId);
                $this->updateRouter(
                    $amenity, $request, $this->controllerName, $languageId
                );
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function destroy($id, $languageId){
        DB::beginTransaction();
        try{
            $amenity = $this->amenityRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\AmenityController'],
            ]);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function createAmenity($request){
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['code'] = ($request->input('code')) ? $request->input('code') : generate_code($request->input('name'));
        $amenity = $this->amenityRepository->create($payload);
        return $amenity;
    }

    private function updateAmenity($amenity, $request){
        $payload = $request->only($this->payload());
        $payload['code'] = ($request->input('code')) ? $request->input('code') : generate_code($request->input('name'));
        $flag = $this->amenityRepository->update($amenity->id, $payload);
        return $flag;
    }

    private function updateLanguageForAmenity($amenity, $request, $languageId){
        $payload = $this->formatLanguagePayload($amenity, $request, $languageId);
        $amenity->languages()->detach([$languageId, $amenity->id]);
        $language = $this->amenityRepository->createPivot($amenity, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($amenity, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['amenity_id'] = $amenity->id;
        return $payload;
    }

    private function paginateSelect(){
        return [
            'amenities.id', 
            'amenities.amenity_catalogue_id',
            'amenities.publish',
            'amenities.image',
            'amenities.order',
            'amenities.code',
            'tb2.name', 
            'tb2.canonical',
            'cat_lang.name as catalogue_name',
        ];
    }

    private function payload(){
        return [
            'amenity_catalogue_id',
            'publish',
            'image',
            'icon',
            'order',
            'code',
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
