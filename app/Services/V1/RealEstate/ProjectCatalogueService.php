<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectCatalogueService extends BaseService
{
    protected $projectCatalogueRepository;
    protected $nestedset;

    public function __construct(
        ProjectCatalogueRepository $projectCatalogueRepository
    ) {
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'project_catalogues',
            'foreignkey' => 'project_catalogue_id',
            'language_id' => 1,
        ]);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ],
        ];
        $pagination = $this->projectCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perPage,
            ['path' => 'project/catalogue/index'],
            ['project_catalogues.lft', 'ASC'],
            [
                ['project_catalogue_language as tb2', 'tb2.project_catalogue_id', '=', 'project_catalogues.id']
            ]
        );
        return $pagination;
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only(['parent_id', 'image', 'publish', 'order']);
            $payload['user_id'] = Auth::id();
            $projectCatalogue = $this->projectCatalogueRepository->create($payload);

            if ($projectCatalogue->id > 0) {
                $this->updateLanguageForProjectCatalogue($projectCatalogue, $request, $languageId);
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
            $payload = $request->only(['parent_id', 'image', 'publish', 'order']);
            $projectCatalogue = $this->projectCatalogueRepository->update($id, $payload);

            if ($projectCatalogue) {
                $this->updateLanguageForProjectCatalogue($projectCatalogue, $request, $languageId);
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->projectCatalogueRepository->delete($id);
            $this->nestedset();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function updateLanguageForProjectCatalogue($projectCatalogue, $request, $languageId)
    {
        $payload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical']);
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['project_catalogue_id'] = $projectCatalogue->id;

        $projectCatalogue->languages()->detach($languageId);
        $this->projectCatalogueRepository->createPivot($projectCatalogue, $payload, 'languages');
    }

    public function nestedset()
    {
        $this->nestedset->Get('level ASC, order ASC');
        $this->nestedset->Recursive(0, $this->nestedset->Set());
        $this->nestedset->Action();
    }

    private function paginateSelect()
    {
        return [
            'project_catalogues.id',
            'project_catalogues.publish',
            'project_catalogues.image',
            'project_catalogues.level',
            'project_catalogues.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }
}
