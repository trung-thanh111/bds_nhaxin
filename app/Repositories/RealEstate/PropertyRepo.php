<?php  
namespace App\Repositories\RealEstate;
use App\Models\Property;
use App\Repositories\BaseRepository;

class PropertyRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        Property $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}