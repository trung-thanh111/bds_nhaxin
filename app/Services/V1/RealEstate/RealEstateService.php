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
    ) {
        $this->realEstateRepository = $realEstateRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'RealEstateController';
    }

    private function whereRaw($request, $languageId, $realEstateCatalogue = null)
    {
        $rawCondition = [];
        $catIds = [];

        if ($request->has('real_estate_catalogue_id')) {
            $input = $request->input('real_estate_catalogue_id');
            if (is_array($input)) {
                $catIds = array_filter(array_map('intval', $input));
            } else if (!empty($input) && intval($input) > 0) {
                $catIds = [intval($input)];
            }
        }

        if (empty($catIds) && $request->filled('real_estate_catalogue_id')) {
            $input = $request->input('real_estate_catalogue_id');
            if (is_string($input) && intval($input) > 0) {
                $catIds = [intval($input)];
            }
        }

        if (empty($catIds) && !is_null($realEstateCatalogue)) {
            $catIds = [$realEstateCatalogue->id];
        }

        if (!empty($catIds)) {
            $placeholders = implode(',', array_fill(0, count($catIds), '?'));
            $rawCondition['whereRaw'] = [
                [
                    "real_estates.real_estate_catalogue_id IN (
                        SELECT id
                        FROM real_estate_catalogues
                        WHERE EXISTS (
                            SELECT 1 FROM real_estate_catalogues as pc 
                            WHERE pc.id IN ($placeholders)
                            AND real_estate_catalogues.lft >= pc.lft 
                            AND real_estate_catalogues.rgt <= pc.rgt
                        )
                    )",
                    array_merge($catIds)
                ]
            ];
        }

        return $rawCondition;
    }

    public function paginate($request, $languageId, $realEstateCatalogue = null, $page = 1, $extend = [], $sort = null, $attributeId = null)
    {
        if (!is_null($realEstateCatalogue) || !is_null($attributeId)) {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        }
        $perPage = (!is_null($realEstateCatalogue))  ? 8 : 20;
        $keywords = array_filter(explode(' ', $request->input('keyword')));
        $condition = [
            'keyword' => !empty($keywords) ? $keywords : null,
            'publish' => $request->integer('publish', 2),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ],
            'fieldSearch' => ['tb2.name'],
        ];

        // Address Filter
        foreach (['province_code', 'district_code', 'ward_code', 'old_province_code', 'old_district_code', 'old_ward_code'] as $f) {
            if ($request->filled($f) && $request->input($f) != '0') {
                $condition['where'][] = ['real_estates.' . $f, '=', $request->input($f)];
            }
        }

        // Transaction Type
        if ($request->filled('transaction_type')) {
            $condition['where'][] = ['real_estates.transaction_type', '=', $request->input('transaction_type')];
        }

        // Area Filter
        if ($request->filled('area_min')) {
            $condition['where'][] = ['real_estates.area', '>=', $request->input('area_min')];
        }
        if ($request->filled('area_max')) {
            $condition['where'][] = ['real_estates.area', '<=', $request->input('area_max')];
        }
        if ($request->filled('area') && strpos($request->input('area'), '-') !== false) {
            $parts = explode('-', $request->input('area'));
            if (isset($parts[0]) && is_numeric($parts[0]) && $parts[0] > 0) {
                $condition['where'][] = ['real_estates.area', '>=', $parts[0]];
            }
            if (isset($parts[1]) && is_numeric($parts[1]) && $parts[1] < 99999) {
                $condition['where'][] = ['real_estates.area', '<=', $parts[1]];
            }
        }

        // Price Filter logic
        $priceMultiplier = ($request->input('transaction_type') == '75') ? 1000000 : 1000000000;
        $priceField = ($request->input('transaction_type') == '75') ? 'real_estates.price_rent' : 'real_estates.price_sale';

        if ($request->filled('price_min')) {
            $condition['where'][] = [$priceField, '>=', $request->input('price_min') * $priceMultiplier];
        }
        if ($request->filled('price_max')) {
            $condition['where'][] = [$priceField, '<=', $request->input('price_max') * $priceMultiplier];
        }
        if ($request->filled('price') && strpos($request->input('price'), '-') !== false) {
            $parts = explode('-', $request->input('price'));
            if (isset($parts[0]) && is_numeric($parts[0]) && $parts[0] > 0) {
                $condition['where'][] = [$priceField, '>=', $parts[0] * $priceMultiplier];
            }
            if (isset($parts[1]) && is_numeric($parts[1]) && $parts[1] < 9999) {
                $condition['where'][] = [$priceField, '<=', $parts[1] * $priceMultiplier];
            }
            // Trường hợp Thỏa thuận (0-0)
            if (isset($parts[0]) && $parts[0] == 0 && isset($parts[1]) && $parts[1] == 0) {
                $condition['where'][] = [$priceField, '=', 0];
            }
        }

        // Specs Filter
        if ($request->filled('bedrooms')) {
            $condition['where'][] = ['real_estates.bedrooms', '=', $request->input('bedrooms')];
        }
        if ($request->filled('bathrooms')) {
            $condition['where'][] = ['real_estates.bathrooms', '=', $request->input('bathrooms')];
        }

        // Directions
        if ($request->filled('house_direction')) {
            $condition['where'][] = ['real_estates.house_direction', '=', $request->input('house_direction')];
        }
        if ($request->filled('balcony_direction')) {
            $condition['where'][] = ['real_estates.balcony_direction', '=', $request->input('balcony_direction')];
        }

        $paginationConfig = [
            'path' => ($extend['path']) ?? 'real/estate/index',
            'groupBy' => $this->paginateSelect()
        ];


        $orderBy = isset($sort) ? $sort : ['real_estates.id', 'DESC'];
        $relations = ['languages', 'amenities.languages', 'catalogue'];
        $rawQuery = $this->whereRaw($request, $languageId, $realEstateCatalogue);

        if (!is_null($attributeId)) {
            $rawQuery['whereRaw'][] = [
                '(real_estates.house_direction = ? OR real_estates.balcony_direction = ? OR real_estates.ownership_type = ? OR real_estates.price_unit = ? OR real_estates.floor = ? OR real_estates.transaction_type = ?)',
                [$attributeId, $attributeId, $attributeId, $attributeId, $attributeId, $attributeId]
            ];
        }

        $joins = [
            ['real_estate_language as tb2', 'tb2.real_estate_id', '=', 'real_estates.id'],
            [DB::raw('(SELECT real_estate_catalogue_id, name, language_id FROM real_estate_catalogue_language WHERE language_id = ' . $languageId . ') as cat_lang'), 'cat_lang.real_estate_catalogue_id', '=', 'real_estates.real_estate_catalogue_id', 'left'],
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

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $realEstate = $this->createRealEstate($request);
            if ($realEstate->id > 0) {
                $this->updateLanguageForRealEstate($realEstate, $request, $languageId);
                $this->updateCatalogueForRealEstate($realEstate, $request);
                $this->updateAmenitiesForRealEstate($realEstate, $request);
                $this->createRouter($realEstate, $request, $this->controllerName, $languageId);
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
            $realEstate = $this->realEstateRepository->findById($id);
            if ($this->uploadRealEstate($realEstate, $request)) {
                $this->updateLanguageForRealEstate($realEstate, $request, $languageId);
                $this->updateCatalogueForRealEstate($realEstate, $request);
                $this->updateAmenitiesForRealEstate($realEstate, $request);
                $this->updateRouter(
                    $realEstate,
                    $request,
                    $this->controllerName,
                    $languageId
                );
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $realEstate = $this->realEstateRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\RealEstateController'],
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            // echo $e->getMessage();die();
            return false;
        }
    }

    private function createRealEstate($request)
    {
        $payload = $this->formatPayload($request);
        $payload['user_id'] = Auth::id();
        $realEstate = $this->realEstateRepository->create($payload);
        $code = trim($payload['code'] ?? '');
        if ($realEstate->id > 0 && ($code === '' || (strlen($code) >= 4 && substr($code, -4) === 'SXG-'))) {
            $timePart = strtoupper(base_convert(date('YmdHis'), 10, 36));
            $prefix = ($code !== '') ? $code : 'BDS-' . $timePart . '-SXG-';
            $newCode = $prefix . $realEstate->id;
            $this->realEstateRepository->update($realEstate->id, ['code' => $newCode]);
            $realEstate->code = $newCode;
        }
        return $realEstate;
    }

    private function uploadRealEstate($realEstate, $request)
    {
        $payload = $this->formatPayload($request);
        $result = $this->realEstateRepository->update($realEstate->id, $payload);
        $code = trim($payload['code'] ?? '');
        if ($result && empty($realEstate->code) && ($code === '' || (strlen($code) >= 4 && substr($code, -4) === 'SXG-'))) {
            $timePart = strtoupper(base_convert(date('YmdHis'), 10, 36));
            $prefix = ($code !== '') ? $code : 'BDS-' . $timePart . '-SXG-';
            $newCode = $prefix . $realEstate->id;
            $this->realEstateRepository->update($realEstate->id, ['code' => $newCode]);
        }
        return $result;
    }

    private function formatPayload($request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        if (isset($payload['price_sale'])) {
            $payload['price_sale'] = str_replace('.', '', $payload['price_sale']);
        }
        if (isset($payload['price_rent'])) {
            $payload['price_rent'] = str_replace('.', '', $payload['price_rent']);
        }

        // Handle New Address Names (After 01/07)
        if (!empty($payload['province_code'])) {
            $payload['province_name'] = $this->getLocationNameFromJson('after', $payload['province_code']);
        }
        if (!empty($payload['district_code'])) {
            $payload['district_name'] = $this->getLocationNameFromJson('after', $payload['district_code']);
        }
        if (!empty($payload['ward_code'])) {
            $payload['ward_name'] = $this->getLocationNameFromJson('after', $payload['ward_code']);
        }

        // Handle Old Address Names (Before 01/07)
        if (!empty($payload['old_province_code'])) {
            $payload['old_province_name'] = $this->getLocationNameFromJson('before', $payload['old_province_code']);
        }
        if (!empty($payload['old_district_code'])) {
            $payload['old_district_name'] = $this->getLocationNameFromJson('before', $payload['old_district_code']);
        }
        if (!empty($payload['old_ward_code'])) {
            $payload['old_ward_name'] = $this->getLocationNameFromJson('before', $payload['old_ward_code']);
        }

        return $payload;
    }

    private function getLocationNameFromJson($source, $codename)
    {
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if (!\Illuminate\Support\Facades\File::exists($filePath)) return '';
        $data = json_decode(\Illuminate\Support\Facades\File::get($filePath), true);

        return $this->searchNameRecursive($data, $codename);
    }

    private function searchNameRecursive($items, $codename)
    {
        foreach ($items as $item) {
            if ($item['codename'] == $codename) {
                return $item['name'];
            }
            if (isset($item['districts'])) {
                $res = $this->searchNameRecursive($item['districts'], $codename);
                if ($res) return $res;
            }
            if (isset($item['wards'])) {
                $res = $this->searchNameRecursive($item['wards'], $codename);
                if ($res) return $res;
            }
        }
        return null;
    }

    private function updateLanguageForRealEstate($realEstate, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $realEstate->id, $languageId);
        $realEstate->languages()->detach([$languageId, $realEstate->id]);
        return $this->realEstateRepository->createPivot($realEstate, $payload, 'languages');
    }

    private function updateCatalogueForRealEstate($realEstate, $request)
    {
        // Now using 1-N, real_estate_catalogue_id is in real_estates table
        return true;
    }

    private function updateAmenitiesForRealEstate($realEstate, $request)
    {
        $realEstate->amenities()->sync($request->input('amenities'));
    }

    private function formatLanguagePayload($payload, $realEstateId, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['real_estate_id'] = $realEstateId;
        return $payload;
    }


    private function catalogue($request)
    {
        if ($request->input('catalogue') != null) {
            return array_unique(array_merge($request->input('catalogue'), [$request->real_estate_catalogue_id]));
        }
        return [$request->real_estate_catalogue_id];
    }

    private function paginateSelect()
    {
        return [
            'real_estates.id',
            'real_estates.publish',
            'real_estates.image',
            'real_estates.order',
            'real_estates.created_at',
            'real_estates.updated_at',
            'real_estates.code',
            'real_estates.area',
            'real_estates.usable_area',
            'real_estates.land_area',
            'real_estates.bedrooms',
            'real_estates.bathrooms',
            'real_estates.house_direction',
            'real_estates.balcony_direction',
            'real_estates.ownership_type',
            'real_estates.road_width',
            'real_estates.floor_count',
            'real_estates.price_sale',
            'real_estates.price_rent',
            'real_estates.price_unit',
            'real_estates.transaction_type',
            'real_estates.province_name',
            'real_estates.district_name',
            'real_estates.ward_name',
            'real_estates.old_province_name',
            'real_estates.old_district_name',
            'real_estates.old_ward_name',
            'real_estates.iframe_map',
            'tb2.name',
            'tb2.description',
            'tb2.canonical',
            'cat_lang.name as catalogue_name',
        ];
    }

    private function payload()
    {
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

    private function payloadLanguage()
    {
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
