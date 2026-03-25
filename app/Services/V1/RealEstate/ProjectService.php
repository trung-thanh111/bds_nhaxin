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
            'real_estate_id',
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
            'extra_fields'
        ]);

        return $payload;
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
            'projects.status',
            'tb2.name',
            'tb2.canonical',
            'cat_lang.name as catalogue_name',
        ];
    }
}
