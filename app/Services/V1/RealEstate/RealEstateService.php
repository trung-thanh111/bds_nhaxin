<?php

namespace App\Services\V1\RealEstate;
use App\Services\V1\BaseService;

use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\Core\RouterRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Class RealEstateService
 * @package App\Services
 */
class RealEstateService extends BaseService
{
    protected $realEstateRepository;
    protected $routerRepository;
    
    public function __construct(
        RealEstateRepository $realEstateRepository,
        RouterRepository $routerRepository,
    ){
        $this->realEstateRepository = $realEstateRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'RealEstateController';
    }

    private function whereRaw($request, $languageId, $realEstateCatalogue = null){
        $rawCondition = [];
        if($request->integer('real_estate_catalogue_id') > 0 || !is_null($realEstateCatalogue)){
            $catId = ($request->integer('real_estate_catalogue_id') > 0) ? $request->integer('real_estate_catalogue_id') : $realEstateCatalogue->id;
            $rawCondition['whereRaw'] =  [
                [
                    'real_estates.real_estate_catalogue_id IN (
                        SELECT id
                        FROM real_estate_catalogues
                        JOIN real_estate_catalogue_language ON real_estate_catalogues.id = real_estate_catalogue_language.real_estate_catalogue_id
                        WHERE lft >= (SELECT lft FROM real_estate_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM real_estate_catalogues as pc WHERE pc.id = ?)
                        AND real_estate_catalogue_language.language_id = '.$languageId.'
                    )',
                    [$catId, $catId]
                ]
            ];
            
        }
        return $rawCondition;
    }

    public function paginate($request, $languageId, $realEstateCatalogue = null, $page = 1, $extend = [], $sort = null){
        if(!is_null($realEstateCatalogue)){
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        }
        $perPage = (!is_null($realEstateCatalogue))  ? 8 : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ],
        ];

        $paginationConfig = [
            'path' => ($extend['path']) ?? 'real/estate/index', 
            'groupBy' => $this->paginateSelect()
        ];


        $orderBy = isset($sort) ? $sort : ['real_estates.id', 'DESC'];
        $relations = ['catalogue'];
        $rawQuery = $this->whereRaw($request, $languageId, $realEstateCatalogue);

        $joins = [
            ['real_estate_language as tb2', 'tb2.real_estate_id', '=', 'real_estates.id'],
            [DB::raw('(SELECT real_estate_catalogue_id, name, language_id FROM real_estate_catalogue_language WHERE language_id = '.$languageId.') as cat_lang'), 'cat_lang.real_estate_catalogue_id', '=', 'real_estates.real_estate_catalogue_id', 'left'],
        ];

        $realEstates = $this->realEstateRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            $paginationConfig,  
            $orderBy,
            $joins,  
            $relations,
            $rawQuery
        ); 

        return $realEstates;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $realEstate = $this->createRealEstate($request);
            if($realEstate->id > 0){
                $this->updateLanguageForRealEstate($realEstate, $request, $languageId);
                $this->updateCatalogueForRealEstate($realEstate, $request);
                $this->updateAmenitiesForRealEstate($realEstate, $request);
                $this->createRouter($realEstate, $request, $this->controllerName, $languageId);
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
            $realEstate = $this->realEstateRepository->findById($id);
            if($this->uploadRealEstate($realEstate, $request)){
                $this->updateLanguageForRealEstate($realEstate, $request, $languageId);
                $this->updateCatalogueForRealEstate($realEstate, $request);
                $this->updateAmenitiesForRealEstate($realEstate, $request);
                $this->updateRouter(
                    $realEstate, $request, $this->controllerName, $languageId
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

    public function destroy($id){
        DB::beginTransaction();
        try{
            $realEstate = $this->realEstateRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\RealEstateController'],
            ]);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            // echo $e->getMessage();die();
            return false;
        }
    }

    private function createRealEstate($request){
        $payload = $this->formatPayload($request);
        $payload['user_id'] = Auth::id();
        if(empty($payload['code'])){
            $payload['code'] = generate_code($request->input('name'));
        }
        $realEstate = $this->realEstateRepository->create($payload);
        return $realEstate;
    }

    private function uploadRealEstate($realEstate, $request){
        $payload = $this->formatPayload($request);
        if(empty($payload['code'])){
            $payload['code'] = generate_code($request->input('name'));
        }
        return $this->realEstateRepository->update($realEstate->id, $payload);
    }

    private function formatPayload($request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        if (isset($payload['price_sale'])) {
            $payload['price_sale'] = str_replace('.', '', $payload['price_sale']);
        }
        if (isset($payload['price_rent'])) {
            $payload['price_rent'] = str_replace('.', '', $payload['price_rent']);
        }
        
        // Handle New Address Names (After 01/07)
        if(!empty($payload['province_code'])){
            $payload['province_name'] = $this->getLocationNameFromJson('after', $payload['province_code']);
        }
        if(!empty($payload['district_code'])){
            $payload['district_name'] = $this->getLocationNameFromJson('after', $payload['district_code']);
        }
        if(!empty($payload['ward_code'])){
            $payload['ward_name'] = $this->getLocationNameFromJson('after', $payload['ward_code']);
        }

        // Handle Old Address Names (Before 01/07)
        if(!empty($payload['old_province_code'])){
            $payload['old_province_name'] = $this->getLocationNameFromJson('before', $payload['old_province_code']);
        }
        if(!empty($payload['old_district_code'])){
            $payload['old_district_name'] = $this->getLocationNameFromJson('before', $payload['old_district_code']);
        }
        if(!empty($payload['old_ward_code'])){
            $payload['old_ward_name'] = $this->getLocationNameFromJson('before', $payload['old_ward_code']);
        }

        return $payload;
    }

    private function getLocationNameFromJson($source, $codename){
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if(!\Illuminate\Support\Facades\File::exists($filePath)) return '';
        $data = json_decode(\Illuminate\Support\Facades\File::get($filePath), true);
        
        return $this->searchNameRecursive($data, $codename);
    }

    private function searchNameRecursive($items, $codename){
        foreach($items as $item){
            if($item['codename'] == $codename){
                return $item['name'];
            }
            if(isset($item['districts'])){
                $res = $this->searchNameRecursive($item['districts'], $codename);
                if($res) return $res;
            }
            if(isset($item['wards'])){
                $res = $this->searchNameRecursive($item['wards'], $codename);
                if($res) return $res;
            }
        }
        return null;
    }

    private function updateLanguageForRealEstate($realEstate, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $realEstate->id, $languageId);
        $realEstate->languages()->detach([$languageId, $realEstate->id]);
        return $this->realEstateRepository->createPivot($realEstate, $payload, 'languages');
    }

    private function updateCatalogueForRealEstate($realEstate, $request){
        // Now using 1-N, real_estate_catalogue_id is in real_estates table
        return true;
    }

    private function updateAmenitiesForRealEstate($realEstate, $request){
        $realEstate->amenities()->sync($request->input('amenities'));
    }

    private function formatLanguagePayload($payload, $realEstateId, $languageId){
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['real_estate_id'] = $realEstateId;
        return $payload;
    }


    private function catalogue($request){
        if($request->input('catalogue') != null){
            return array_unique(array_merge($request->input('catalogue'), [$request->real_estate_catalogue_id]));
        }
        return [$request->real_estate_catalogue_id];
    }
    
    private function paginateSelect(){
        return [
            'real_estates.id', 
            'real_estates.publish',
            'real_estates.image',
            'real_estates.order',
            'real_estates.created_at',
            'real_estates.code',
            'real_estates.area',
            'tb2.name', 
            'tb2.description',
            'tb2.canonical',
            'cat_lang.name as catalogue_name',
        ];
    }

    private function payload(){
        return [
            'code',
            'real_estate_catalogue_id',
            'project_id',
            'agent_id',
            'image',
            'old_province_code',
            'old_province_name',
            'old_district_code',
            'old_district_name',
            'old_ward_code',
            'old_ward_name',
            'province_code',
            'province_name',
            'district_code',
            'district_name',
            'ward_code',
            'ward_name',
            'street',
            'iframe_map',
            'street',
            'iframe_map',
            'area',
            'usable_area',
            'land_area',
            'year_built',
            'floor_count',
            'floor',
            'total_floors',
            'bedrooms',
            'bathrooms',
            'house_direction',
            'balcony_direction',
            'view',
            'ownership_type',
            'land_type',
            'land_width',
            'land_length',
            'road_frontage',
            'road_width',
            'block_tower',
            'apartment_code',
            'interior',
            'video_url',
            'tour_url',
            'album',
            'price_sale',
            'price_rent',
            'price_unit',
            'transaction_type',
            'publish',
            'order',
            'follow'
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
            'canonical',
        ];
    }
}
