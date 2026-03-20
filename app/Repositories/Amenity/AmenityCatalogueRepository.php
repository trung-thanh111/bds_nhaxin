<?php

namespace App\Repositories\Amenity;

use App\Models\AmenityCatalogue;
use App\Repositories\BaseRepository;

/**
 * Class AmenityCatalogueRepository
 * @package App\Repositories\Amenity
 */
class AmenityCatalogueRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        AmenityCatalogue $model
    ){
        $this->model = $model;
    }

    public function getAmenityCatalogueById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'amenity_catalogues.id',
                'amenity_catalogues.parent_id',
                'amenity_catalogues.image',
                'amenity_catalogues.icon',
                'amenity_catalogues.publish',
                'amenity_catalogues.order',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('amenity_catalogue_language as tb2', 'tb2.amenity_catalogue_id', '=','amenity_catalogues.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

    public function getAll(int $languageId = 0){
        return $this->model->with(['amenity_catalogue_language' => function($query) use ($languageId){
            $query->where('language_id', $languageId);
        }, ])->get();
    }
}
