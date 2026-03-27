<?php

namespace App\Repositories\RealEstate;

use App\Models\ProjectCatalogue;
use App\Repositories\BaseRepository;

class ProjectCatalogueRepository extends BaseRepository
{
    protected $model;

    public function __construct(ProjectCatalogue $model)
    {
        $this->model = $model;
    }

    public function getProjectCatalogueById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
            'project_catalogues.id',
            'project_catalogues.parent_id',
            'project_catalogues.lft',
            'project_catalogues.rgt',
            'project_catalogues.image',
            'project_catalogues.publish',
            'project_catalogues.order',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.meta_title',
            'tb2.meta_keyword',
            'tb2.meta_description',
            'tb2.canonical',
        ])
            ->join('project_catalogue_language as tb2', 'tb2.project_catalogue_id', '=', 'project_catalogues.id')
            ->where('tb2.language_id', '=', $language_id)
            ->find($id);
    }
}
