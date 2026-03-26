<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\Core\RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Class ProjectService
 * @package App\Services
 */
class ProjectService extends BaseService
{
    protected $projectRepository;
    protected $routerRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        RouterRepository $routerRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'ProjectController';
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->integer('perpage') > 0 ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ],
        ];

        if ($request->integer('project_catalogue_id') > 0) {
            $condition['where'][] = ['projects.project_catalogue_id', '=', $request->integer('project_catalogue_id')];
        }

        $paginationConfig = [
            'path' => 'project/index',
            'groupBy' => $this->paginateSelect()
        ];

        $joins = [
            ['project_language as tb2', 'tb2.project_id', '=', 'projects.id'],
            [DB::raw('(SELECT project_catalogue_id, name FROM project_catalogue_language WHERE language_id = ' . $languageId . ') as cat_lang'), 'cat_lang.project_catalogue_id', '=', 'projects.project_catalogue_id', 'left'],
        ];

        return $this->projectRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perPage,
            $paginationConfig,
            ['projects.id', 'DESC'],
            $joins
        );
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $this->formatPayload($request);
            $project = $this->projectRepository->create($payload);
                if ($project->id > 0) {
                    $this->updateLanguageForProject($project, $request, $languageId);
                    $this->createRouter($project, $request, $this->controllerName, $languageId);
                    
                    if ($request->has('related_projects')) {
                        $project->related_projects()->sync($request->input('related_projects'));
                    }
                    
                    if ($request->has('amenities')) {
                        $project->amenities()->sync($request->input('amenities'));
                    }
                    
                    if ($request->has('floorplans')) {
                        $project->floorplans()->sync($request->input('floorplans'));
                    }
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
            $payload = $this->formatPayload($request);
            $project = $this->projectRepository->update($id, $payload);
                if ($project) {
                    $project = $this->projectRepository->findById($id);
                    $this->updateLanguageForProject($project, $request, $languageId);
                    $this->updateRouter($project, $request, $this->controllerName, $languageId);
                    
                    if ($request->has('related_projects')) {
                        $project->related_projects()->sync($request->input('related_projects'));
                    }
                    
                    if ($request->has('amenities')) {
                        $project->amenities()->sync($request->input('amenities'));
                    }
                    
                    if ($request->has('floorplans')) {
                        $project->floorplans()->sync($request->input('floorplans'));
                    }
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
            $this->projectRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\ProjectController'],
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function formatPayload($request)
    {
        $payload = $request->only([
            'project_catalogue_id',
            'is_project',
            'apartment_count',
            'block_count',
            'area',
            'legal_status',
            'status',
            'publish',
            'is_featured',
            'is_hot',
            'is_urgent',
            'order',
            'cover_image',
            'video_url',
            'video_embed',
            'virtual_tour_url',
            'province_code',
            'province_name',
            'district_code',
            'district_name',
            'ward_code',
            'ward_name',
            'old_province_code',
            'old_province_name',
            'old_district_code',
            'old_district_name',
            'old_ward_code',
            'old_ward_name',
            'street',
            'album',
            'iframe_map',
            'lat',
            'long',
            'extra_fields'
        ]);

        $payload['name'] = $request->input('name');
        $payload['slug'] = Str::slug($request->input('canonical'));
        $payload['album'] = $this->formatAlbum($request);

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

    private function updateLanguageForProject($project, $request, $languageId)
    {
        $payload = $request->only([
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        ]);
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['project_id'] = $project->id;
        $project->languages()->detach([$languageId, $project->id]);
        return $this->projectRepository->createPivot($project, $payload, 'languages');
    }

    private function paginateSelect()
    {
        return [
            'projects.id',
            'projects.publish',
            'projects.cover_image',
            'projects.order',
            'projects.created_at',
            'projects.updated_at',
            'projects.status',
            'projects.code',
            'projects.area',
            'projects.apartment_count',
            'projects.block_count',
            'projects.legal_status',
            'projects.province_name',
            'projects.district_name',
            'projects.ward_name',
            'projects.old_province_name',
            'projects.old_district_name',
            'projects.old_ward_name',
            'projects.iframe_map',
            'tb2.name',
            'tb2.canonical',
            'cat_lang.name as catalogue_name',
        ];
    }
}
