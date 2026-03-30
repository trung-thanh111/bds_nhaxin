<?php

namespace App\Repositories\RealEstate;

use App\Models\RealEstate;
use App\Repositories\BaseRepository;

/**
 * Class RealEstateRepository
 * @package App\Repositories\RealEstate
 */
class RealEstateRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        RealEstate $model
    ) {
        $this->model = $model;
        parent::__construct($model);
    }

    public function getRealEstateById(int $id = 0, $language_id = 0)
    {
        return $this->model->select(
            [
                'real_estates.*',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
            ->join('real_estate_language as tb2', 'tb2.real_estate_id', '=', 'real_estates.id')
            ->where('tb2.language_id', '=', $language_id)
            ->with(['languages', 'amenities.languages'])
            ->find($id);
    }
}
