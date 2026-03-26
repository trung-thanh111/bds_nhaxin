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
            'projects.project_catalogue_id',
            'projects.cover_image',
            'projects.album',
            'projects.status',
            'projects.apartment_count',
            'projects.block_count',
            'projects.area',
            'projects.legal_status',
            'projects.publish',
            'projects.order',
            'projects.province_code',
            'projects.province_name',
            'projects.district_code',
            'projects.district_name',
            'projects.ward_code',
            'projects.ward_name',
            'projects.old_province_code',
            'projects.old_province_name',
            'projects.old_district_code',
            'projects.old_district_name',
            'projects.old_ward_code',
            'projects.old_ward_name',
            'projects.street',
            'projects.created_at',
            'projects.updated_at',
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
