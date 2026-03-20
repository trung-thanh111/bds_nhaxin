<?php

namespace App\Repositories\RealEstate;

use App\Models\GalleryCatalogue;
use App\Repositories\BaseRepository;

class GalleryCatalogueRepo extends BaseRepository
{

    protected $model;

    public function __construct(
        GalleryCatalogue $model
    ) {
        $this->model = $model;
        parent::__construct($model);
    }
}
