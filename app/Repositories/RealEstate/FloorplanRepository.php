<?php

namespace App\Repositories\RealEstate;

use App\Repositories\BaseRepository;
use App\Models\Floorplan;

class FloorplanRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        Floorplan $model
    ){
        $this->model = $model;
    }

    public function getFloorplanById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'floorplans.id',
                'floorplans.real_estate_id',
                'floorplans.image',
                'floorplans.publish',
                'floorplans.order',
                'tb2.name',
                'tb2.description',
            ])
            ->join('floorplan_language as tb2', 'tb2.floorplan_id', '=', 'floorplans.id')
            ->where('tb2.language_id', '=', $language_id)
            ->find($id);
    }
}
