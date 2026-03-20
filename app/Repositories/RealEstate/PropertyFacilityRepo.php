<?php  
namespace App\Repositories\RealEstate;
use App\Models\PropertyFacility;
use App\Repositories\BaseRepository;

class PropertyFacilityRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        PropertyFacility $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}