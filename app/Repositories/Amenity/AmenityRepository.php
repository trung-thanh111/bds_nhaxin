<?php

namespace App\Repositories\Amenity;

use App\Models\Amenity;
use App\Repositories\BaseRepository;

/**
 * Class AmenityRepository
 * @package App\Repositories\Amenity
 */
class AmenityRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        Amenity $model
    ){
        $this->model = $model;
    }

    public function getAmenityById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'amenities.id',
                'amenities.amenity_catalogue_id',
                'amenities.image',
                'amenities.icon',
                'amenities.code',
                'amenities.publish',
                'amenities.order',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('amenity_language as tb2', 'tb2.amenity_id', '=','amenities.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

    public function getAmenityCatalogueWhereIn($whereIn, $whereInField = 'id', $language_id){
        return $this->model->select([
            'amenities.id',
            'tb2.name',
        ])
        ->join('amenity_language as tb2', 'tb2.amenity_id', '=','amenities.id')
        ->where('tb2.language_id', '=', $language_id)
        ->where([config('apps.general.defaultPublish')])
        ->whereIn($whereInField, $whereIn)
        ->get();
    }
}
