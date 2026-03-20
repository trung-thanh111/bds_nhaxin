<?php

namespace App\Repositories\RealEstate;

use App\Models\Project;
use App\Repositories\BaseRepository;

/**
 * Class ProjectRepository
 * @package App\Repositories
 */
class ProjectRepository extends BaseRepository
{
    protected $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function getProjectById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
            'projects.id',
            'projects.real_estate_id',
            'projects.project_catalogue_id',
            'projects.agent_id',
            'projects.cover_image',
            'projects.price',
            'projects.price_unit',
            'projects.price_vnd',
            'projects.price_negotiable',
            'projects.transaction_type',
            'projects.status',
            'projects.publish',
            'projects.order',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.canonical',
            'tb2.meta_title',
            'tb2.meta_keyword',
            'tb2.meta_description',
        ])
            ->join('project_language as tb2', 'tb2.project_id', '=', 'projects.id')
            ->where('tb2.language_id', '=', $language_id)
            ->find($id);
    }
}
