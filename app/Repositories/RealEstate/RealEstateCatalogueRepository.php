<?php

namespace App\Repositories\RealEstate;

use App\Models\RealEstateCatalogue;
use App\Repositories\BaseRepository;

/**
 * Class RealEstateCatalogueRepository
 * @package App\Repositories\RealEstate
 */
class RealEstateCatalogueRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        RealEstateCatalogue $model
    ){
        $this->model = $model;
        parent::__construct($model);
    }

    public function getRealEstateCatalogueById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'real_estate_catalogues.id',
                'real_estate_catalogues.parent_id',
                'real_estate_catalogues.image',
                'real_estate_catalogues.icon',
                'real_estate_catalogues.album',
                'real_estate_catalogues.publish',
                'real_estate_catalogues.follow',
                'real_estate_catalogues.lft',
                'real_estate_catalogues.rgt',
                'real_estate_catalogues.created_at',
                'real_estate_catalogues.short_name',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('real_estate_catalogue_language as tb2', 'tb2.real_estate_catalogue_id', '=','real_estate_catalogues.id')
        ->where('tb2.language_id', '=', $language_id)
        ->with(['direct_children.languages'])
        ->find($id);
    }

}
